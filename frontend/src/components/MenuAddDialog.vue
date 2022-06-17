<template>
  <validation-observer ref="observer" slim v-slot="{ invalid }">
    <dialog-card title="Автоматическое создание меню" v-model="opened">
      <v-row v-if="false" no-gutters>
        <v-col>Выберите категории, из которых будет составлено меню:</v-col>
      </v-row>
      <v-row v-if="false" no-gutters class="mt-4">
        <v-col>
          <v-checkbox label="Группа 1" class="ma-0 hide-messages" />
          <v-checkbox label="Группа 2" class="ma-0 hide-messages" />
          <v-checkbox label="Группа 3" class="ma-0 hide-messages" />
        </v-col>
        <v-col>
          <v-checkbox label="Группа 4" class="ma-0 hide-messages" />
          <v-checkbox label="Группа 5" class="ma-0 hide-messages" />
          <v-checkbox label="Группа 6" class="ma-0 hide-messages" />
        </v-col>
      </v-row>
      <v-row no-gutters class="mt-4">
        <v-col>Выберите схему питания:</v-col>
      </v-row>
      <v-row no-gutters>
        <v-col>
          <validation-provider slim rules="required|min:1">
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
      <v-row>
        <v-col>
          <v-btn block color="primary" large :disabled="invalid" @click="storeMenu">Создать</v-btn>
        </v-col>
      </v-row>
      <MenuSchemeAddEditDialog v-model="showSchemeAddEditDialog" :scheme-id="schemeId" @created="schemes.push($event)" @updated="updatedScheme"/>
      <dialog-confirm v-model="showSchemeDeleteConfirm" title="Удалить схему меню" text="Вы действительно хотите удалить эту схему меню?" @confirm="destroyScheme"/>
    </dialog-card>
  </validation-observer>
</template>

<script lang="ts">
import {Component, Prop, Vue, Watch} from 'vue-property-decorator'
import DialogCard from "@/components/DialogCard.vue";
import MenuSchemeAddEditDialog from "@/components/MenuSchemeAddEditDialog.vue";
import {MenuSchemeIndex, MenuSchemeShow} from "@/models/MenuScheme";
import MenuSchemeRepository from "@/repositories/MenuSchemeRepository";
import DialogConfirm from "@/components/DialogConfirm.vue";
import MenuRepository from "@/repositories/MenuRepository";
import {ValidationObserver} from "vee-validate";

@Component({
  components: {DialogConfirm, MenuSchemeAddEditDialog, DialogCard}
})
export default class MenuAddDialog extends Vue {
  $refs!: {
    observer: InstanceType<typeof ValidationObserver>
  }

  @Prop({default: false}) value!: boolean;

  private selectedSchemeId = 0;
  private schemeId = 0;
  private opened = false;
  private showSchemeAddEditDialog = false;
  private schemes: MenuSchemeIndex[] = [];
  private schemeDeleteIndex = 0;
  private showSchemeDeleteConfirm = false;

  mounted(): void {
    this.opened = this.value;
    this.loadSchemes();
  }

  async loadSchemes(): Promise<void> {
    const {data} = await MenuSchemeRepository.index();

    this.schemes = data;
  }

  async destroyScheme(): Promise<void> {
    await MenuSchemeRepository.destroy(this.schemes[this.schemeDeleteIndex].id);

    this.schemes.splice(this.schemeDeleteIndex, 1);
  }

  async storeMenu(): Promise<void> {
    await MenuRepository.store({
      amount: 1,
      categories: [],
      meal_categories: [],
      wishes: [],
      scheme_id: this.selectedSchemeId
    });
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
  }
}
</script>

<style scoped lang="scss">
.hide-messages {
  margin-bottom: -24px !important;
}
</style>
