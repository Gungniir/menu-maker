<template>
  <div class="dc" @click="clickEvent">
    <div class="dc__background"/>
    <div class="dc__actions">
      <v-tooltip bottom open-delay="300">
        <template #activator="{ on, attrs }">
          <v-btn icon v-bind="attrs" v-on="on">
            <v-icon>mdi-pencil</v-icon>
          </v-btn>
        </template>
        Редактировать
      </v-tooltip>
      <v-tooltip bottom open-delay="300">
        <template #activator="{ on, attrs }">
          <v-btn icon v-bind="attrs" v-on="on" @click.stop="destroyCategoryPrompt">
            <v-icon>mdi-delete</v-icon>
          </v-btn>
        </template>
        Удалить
      </v-tooltip>
    </div>
    <div class="dc__name">
      {{ category.name }}
    </div>
    <dialog-confirm v-model="showDeletePrompt" title="Удаление категории" text="Вы действительно хотите удалить эту категорию?" @confirm="destroyCategory"/>
  </div>
</template>

<script lang="ts">
import {Component, Emit, Prop, Vue} from 'vue-property-decorator'
import DialogConfirm from "@/components/DialogConfirm.vue";
import {CategoryIndex} from "@/models/Category";
import CategoryRepository from "@/repositories/CategoryRepository";

@Component({
  components: {DialogConfirm}
})
export default class CategoryCard extends Vue {
  @Prop() readonly category!: CategoryIndex

  private showDeletePrompt = false;

  @Emit('click')
  clickEvent(event: MouseEvent): MouseEvent {
    return event;
  }

  @Emit('deleted')
  deletedEvent(): boolean {
    return true;
  }

  destroyCategoryPrompt(): void {
    this.showDeletePrompt = true;
  }

  async destroyCategory(): Promise<void> {
    await CategoryRepository.destroy(this.category.id);
    this.deletedEvent();
  }
}
</script>

<style scoped lang="scss">
.dc {
  position: relative;
  border-radius: 10px;
  border: #0000000A 1px solid;
  aspect-ratio: 1.5;
  overflow: hidden;
  cursor: pointer;

  .dc__name {
    position: absolute;
    bottom: 0;
    left: 0;

    font-size: 32px;
    font-weight: 500;
    line-height: 39px;
    text-align: center;


    height: 100%;
    width: 100%;

    display: flex;
    align-items: center;
    justify-content: center;

    overflow: hidden;
    text-overflow: ellipsis;

    //background: rgba(255, 255, 255, 0.8);
    //border: 1px solid rgba(0, 0, 0, 0.2);
    //border-radius: 0 0 10px 10px;
  }

  .dc__background {
    height: 100%;
    width: 100%;

    background: #FFEDD3;
  }

  &:hover .dc__actions{
    display: flex;
  }

  .dc__actions {
    display: none;

    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    justify-content: flex-end;
  }
}
</style>
