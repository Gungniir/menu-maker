<template>
    <dialog-card title="Автоматическое создание меню" v-model="opened">
      <validation-observer ref="observer" slim v-slot="{ invalid }">
        <div>
          <v-row no-gutters>
            <v-col>Выберите категории, из которых будет составлено меню:</v-col>
          </v-row>
          <v-row no-gutters class="mt-4">
            <v-col>
              <template v-if="loadingCategories">
                <v-skeleton-loader v-for="index in 3" :key="index" class="fluid mb-2" type="text" height="20" />
              </template>
              <template v-else>
                <v-checkbox v-model="categoriesSelect[index]" v-for="(category, index) of categories" :key="category.id" :label="category.name" class="ma-0 hide-messages" />
              </template>
            </v-col>
          </v-row>
          <v-row no-gutters class="mt-4">
            <v-col>Выберите схему питания:</v-col>
          </v-row>
          <v-row no-gutters>
            <v-col>
              <template v-if="loadingSchemes">
                <v-skeleton-loader v-for="index in 3" :key="index" class="fluid mb-2" type="text" height="25" />
              </template>
              <validation-provider v-else slim rules="required|min:1">
                <v-radio-group v-model="selectedSchemeId" class="ma-0 hide-messages">
                  <v-radio v-for="(scheme, index) of schemes" :key="scheme.id" :value="scheme.id">
                    <template #label>
                      <div class="d-flex align-center" style="width: 100%">
                        <v-tooltip
                          bottom
                          open-delay="300"
                        >
                          <template #activator="{ on, attrs }">
                            <div
                              class="flex-grow-1"
                              style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap"
                              v-on="on"
                              v-bind="attrs"
                            >
                              {{ scheme.name }}
                            </div>
                          </template>
                          {{ scheme.name }}
                        </v-tooltip>
                        <div class="flex-shrink-0">
                          <v-tooltip
                            bottom
                            open-delay="300"
                          >
                            <template #activator="{ on, attrs }">
                              <v-btn icon v-on="on" v-bind="attrs" @click.stop="schemeId = scheme.id; showSchemeAddEditDialog = true">
                                <v-icon>mdi-pencil</v-icon>
                              </v-btn>
                            </template>
                            Редактировать
                          </v-tooltip>
                          <v-tooltip
                            bottom
                            open-delay="300"
                          >
                            <template #activator="{ on, attrs }">
                              <v-btn icon v-on="on" v-bind="attrs" @click.stop="schemeDeleteIndex = index; showSchemeDeleteConfirm = true">
                                <v-icon>mdi-delete</v-icon>
                              </v-btn>
                            </template>
                            Удалить
                          </v-tooltip>
                        </div>
                      </div>
                    </template>
                  </v-radio>
                </v-radio-group>
              </validation-provider>
              <v-btn
                class="ma-0 pa-0"
                plain
                color="primary"
                @click="schemeId = 0; showSchemeAddEditDialog = true"
              >
                Создать новую схему
              </v-btn>
            </v-col>
          </v-row>
          <template v-if="selectedScheme">
            <v-row no-gutters class="mt-4">
              <v-col>Выберите категории для отдельных приёмов пищи:</v-col>
            </v-row>
            <v-row no-gutters class="mt-2">
              <v-col>
                <v-autocomplete
                  v-for="(meal, index) of selectedScheme.meals"
                  v-model="mealCategories[index]"
                  :key="meal.id"
                  item-value="id"
                  item-text="name"
                  clearable
                  persistent-placeholder
                  placeholder="Любая категория"
                  :items="categories"
                  :label="meal.name"
                />
              </v-col>
            </v-row>
          </template>
          <v-row>
            <v-col>
              <v-btn
                block
                color="primary"
                large
                :disabled="invalid || loadingSchemes || loadingCategories"
                :loading="loading"
                @click="storeMenu"
              >
                Создать
              </v-btn>
            </v-col>
          </v-row>
        </div>
      </validation-observer>
      <MenuSchemeAddEditDialog v-model="showSchemeAddEditDialog" :scheme-id="schemeId" @created="schemes.push($event)" @updated="updatedScheme"/>
      <dialog-confirm v-model="showSchemeDeleteConfirm" title="Удалить схему меню" text="Вы действительно хотите удалить эту схему меню?" @confirm="destroyScheme"/>
    </dialog-card>
</template>

<script lang="ts">
import {Component, Emit, Prop, Vue, Watch} from 'vue-property-decorator'
import DialogCard from "@/components/DialogCard.vue";
import MenuSchemeAddEditDialog from "@/components/MenuSchemeAddEditDialog.vue";
import {MenuSchemeIndex, MenuSchemeShow} from "@/models/MenuScheme";
import MenuSchemeRepository from "@/repositories/MenuSchemeRepository";
import DialogConfirm from "@/components/DialogConfirm.vue";
import MenuRepository from "@/repositories/MenuRepository";
import {ValidationObserver} from "vee-validate";
import {MenuShow} from "@/models/Menu";
import CategoryRepository from "@/repositories/CategoryRepository";
import {CategoryIndex} from "@/models/Category";

@Component({
  components: {DialogConfirm, MenuSchemeAddEditDialog, DialogCard}
})
export default class MenuAddDialog extends Vue {
  $refs!: {
    observer: InstanceType<typeof ValidationObserver>
  }

  @Prop({default: false}) value!: boolean;
  @Prop({required: true}) startDate!: string;

  private loading = false;
  private loadingSchemes = false;
  private loadingCategories = false;

  private categories: CategoryIndex[] = [];
  private categoriesSelect: boolean[] = [];

  private selectedSchemeId = 0;
  private schemeId = 0;
  private opened = false;
  private showSchemeAddEditDialog = false;
  private schemes: MenuSchemeIndex[] = [];
  private schemeDeleteIndex = 0;
  private showSchemeDeleteConfirm = false;
  private mealCategories: (number | null)[] = [];

  mounted(): void {
    this.opened = this.value;
    this.loadSchemes();
    this.loadCategories();
  }

  get selectedScheme(): MenuSchemeIndex | null {
    const scheme = this.schemes.find(scheme => scheme.id === this.selectedSchemeId);

    return scheme ?? null;
  }

  private reset(): void {
    this.loading = false;
    this.selectedSchemeId = 0;
    this.schemeId = 0;
  }

  async loadSchemes(): Promise<void> {
    this.loadingSchemes = true;
    const {data} = await MenuSchemeRepository.index();

    this.schemes = data;
    this.loadingSchemes = false;
  }

  async loadCategories(): Promise<void> {
    this.loadingCategories = true;
    let page = 1;
    let lastPage = 1;

    this.categories = [];
    this.categoriesSelect = [];

    while (page <= lastPage) {
      const {data} = await CategoryRepository.paginate(page++);
      lastPage = data.last_page;
      this.categoriesSelect.push(...data.data.map(() => false));
      this.categories.push(...data.data);
    }

    this.loadingCategories = false;
  }

  async destroyScheme(): Promise<void> {
    await MenuSchemeRepository.destroy(this.schemes[this.schemeDeleteIndex].id);

    this.schemes.splice(this.schemeDeleteIndex, 1);
  }

  async storeMenu(): Promise<void> {
    this.loading = true;

    try {
      const {data} = await MenuRepository.store({
        amount: 1,
        start_date: this.startDate,
        categories: this.categories.filter((c, index) => this.categoriesSelect[index]).map(c => c.id),
        meal_categories: this.mealCategories.filter(a => a !== null).map((c_id, index) => ({
          // eslint-disable-next-line @typescript-eslint/no-non-null-assertion
          meal_id: this.selectedScheme!.meals[index].id,
          // eslint-disable-next-line @typescript-eslint/no-non-null-assertion
          category_id: c_id!
        })),
        wishes: [],
        scheme_id: this.selectedSchemeId
      });

      this.createdEvent(data);
      this.opened = false;
    } catch (e) {
      throw e;
    } finally {
      this.loading = false;
    }
  }

  updatedScheme(scheme: MenuSchemeShow): void {
    const index = this.schemes.findIndex(scheme => scheme.id === this.schemeId);

    this.$set(this.schemes, index, scheme);
  }

  @Watch('opened')
  onOpenedChanged(value: boolean): void {
    this.$emit('input', value);
  }

  @Watch('value')
  onValueChanged(value: boolean): void {
    this.opened = value;

    if (value) {
      this.reset();
    }
  }

  @Watch('selectedScheme')
  onSelectedSchemeChanged(): void {
    if (!this.selectedScheme) {
      this.mealCategories = [];
      return;
    }

    this.mealCategories = this.selectedScheme.meals.map(() => null);
  }

  @Emit('created')
  createdEvent(menu: MenuShow): MenuShow {
    return menu;
  }
}
</script>

<style scoped lang="scss">
.hide-messages {
  margin-bottom: -24px !important;
}
</style>
