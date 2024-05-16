import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { ShowCategoriesPageRoutingModule } from './show-categories-routing.module';

import { ShowCategoriesPage } from './show-categories.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    ShowCategoriesPageRoutingModule
  ],
  declarations: [ShowCategoriesPage]
})
export class ShowCategoriesPageModule {}
