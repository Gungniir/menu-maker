<template>
  <dialog-card ref="observer" v-model="opened" :title="(isEdit ? 'Изменить' : 'Добавить') + ' ингредиент'" with-observer v-slot="{ invalid }">
    <div class="ingredients__input-group">
      <validation-provider
        vid="name"
        name="название ингредиента"
        :rules="{
          required: true,
          unique: {
            items: usedNames,
            allowed: ingredientAllowedName,
          },
        }"
        v-slot="{ errors }"
      >
        <v-text-field
          v-model="ingredientName"
          label="Название ингредиента"
          outlined
          persistent-placeholder
          :error-messages="errors"
        />
      </validation-provider>
    </div>
    <div class="ingredients__input-group">
      <v-combobox
        v-model="ingredientType"
        label="Категория ингредиента"
        placeholder="Без категории"
        clearable
        outlined
        persistent-placeholder
        :items="types"
        :search-input.sync="ingredientTypeSearch"
      />
    </div>
    <div class="ingredients__input-group">
      <div class="ingredients__input-label">
        Единицы измерения:
      </div>
      <v-radio-group v-model="ingredientUnit">
        <v-row>
          <v-col>
            <v-radio label="Граммы" value="г" />
            <v-radio label="Килограммы" value="кг" />
            <v-radio label="Штуки" value="шт" />
          </v-col>
          <v-col>
            <v-radio label="Литры" value="л" />
            <v-radio label="Миллилитры" value="мл" />
          </v-col>
        </v-row>
      </v-radio-group>
    </div>
    <div class="ingredients__input-group">
      <validation-provider
        vid="amount"
        name="количество"
        rules="required|integer"
        v-slot="{ errors }"
      >
        <v-text-field
          v-model="ingredientAmount"
          :suffix="ingredientUnit"
          :error-messages="errors"
          label="Количество"
          outlined
          persistent-placeholder
        />
      </validation-provider>
    </div>

    <v-btn color="primary" large :loading="loading" :disabled="invalid" @click="onSubmit">
      {{ isEdit ? 'Изменить' : 'Добавить'}}
    </v-btn>
  </dialog-card>
</template>

<script lang="ts">
import {Component, Emit, Prop, Vue, Watch} from 'vue-property-decorator'
import DialogCard from "@/components/DialogCard.vue";
import {IngredientShow, IngredientUnit} from "@/models/Ingredient";
import IngredientRepository from "@/repositories/IngredientRepository";

@Component({
  components: {DialogCard}
})
export default class IngredientAddEditDialog extends Vue {
  $refs!: {
    observer: InstanceType<typeof DialogCard>
  }

  @Prop({default: false}) value!: boolean;
  @Prop({default: 0}) ingredientId!: number;
  @Prop({default: ''}) ingredientInputName!: string;
  @Prop({default: () => []}) usedNames!: string[];

  get isEdit(): boolean {
    return !!this.ingredientId;
  }

  private opened = false;
  private loading = false;

  private ingredientAllowedName = '';
  private ingredientName = '';
  private ingredientType: string | null = '';
  private ingredientTypeSearch = '';
  private ingredientUnit = IngredientUnit.Grams;
  private ingredientAmount = '0';

  private types: string[] = [];

  mounted(): void {
    this.opened = this.value;
    this.loadTypes();
  }

  private reset(): void {
    this.ingredientName = '';
    this.ingredientAllowedName = '';
    this.ingredientType = '';
    this.ingredientTypeSearch = '';
    this.ingredientUnit = IngredientUnit.Grams;
    this.ingredientAmount = '0';
    this.$refs.observer.reset();
  }

  private onSubmit(): void {
    if (this.isEdit) {
      this.updateIngredient();
    } else {
      this.addIngredient();
    }
  }

  private async addIngredient(): Promise<void> {
    this.loading = true;

    const {data} = await IngredientRepository.store({
      name: this.ingredientName,
      is_perishable: false,
      type: this.ingredientType,
      amount: Number(this.ingredientAmount),
      unit: this.ingredientUnit
    });

    this.opened = false;
    this.loading = false;

    this.createdEvent(data);
  }

  private async updateIngredient(): Promise<void> {
    this.loading = true;

    const {data} = await IngredientRepository.update(this.ingredientId, {
      name: this.ingredientName,
      is_perishable: false,
      type: this.ingredientType ?? this.ingredientTypeSearch,
      amount: Number(this.ingredientAmount),
      unit: this.ingredientUnit
    });

    this.opened = false;
    this.loading = false;

    this.updatedEvent(data);
  }

  private async loadTypes(): Promise<void> {
    const {data} = await IngredientRepository.types();

    this.types = data;
  }

  private async loadIngredient(): Promise<void> {
    this.loading = true;

    const {data} = await IngredientRepository.show(this.ingredientId);

    this.loading = false;
    this.ingredientAllowedName = data.name;
    this.ingredientName = data.name;
    this.ingredientAmount = data.amount.toString();
    this.ingredientType = data.type;
    this.ingredientUnit = data.unit;

    this.loading = false;
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
      this.loadTypes();


      if (this.ingredientInputName) {
        this.ingredientName = this.ingredientInputName;
      }

      if (this.isEdit) {
        this.loadIngredient();
      }
    }
  }

  @Emit('created')
  createdEvent(item: IngredientShow): IngredientShow {
    return item;
  }

  @Emit('updated')
  updatedEvent(item: IngredientShow): IngredientShow {
    return item;
  }
}
</script>

<style scoped lang="scss">

</style>
