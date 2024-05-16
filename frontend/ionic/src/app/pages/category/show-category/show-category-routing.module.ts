import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { ShowCategoryPage } from './show-category.page';

const routes: Routes = [
  {
    path: '',
    component: ShowCategoryPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class ShowCategoryPageRoutingModule {}
