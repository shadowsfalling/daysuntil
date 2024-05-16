import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { EditCountdownPage } from './edit-countdown.page';

const routes: Routes = [
  {
    path: '',
    component: EditCountdownPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class EditCountdownPageRoutingModule {}
