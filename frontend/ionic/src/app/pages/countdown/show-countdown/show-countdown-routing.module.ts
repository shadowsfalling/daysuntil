import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { ShowCountdownPage } from './show-countdown.page';

const routes: Routes = [
  {
    path: '',
    component: ShowCountdownPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class ShowCountdownPageRoutingModule {}
