import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { ShowCategoryPageRoutingModule } from './show-category-routing.module';

import { ShowCategoryPage } from './show-category.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    ShowCategoryPageRoutingModule
  ],
  declarations: [ShowCategoryPage]
})
export class ShowCategoryPageModule {}
