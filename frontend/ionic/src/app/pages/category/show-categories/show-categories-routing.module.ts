import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { ShowCategoriesPage } from './show-categories.page';

const routes: Routes = [
  {
    path: '',
    component: ShowCategoriesPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class ShowCategoriesPageRoutingModule {}
