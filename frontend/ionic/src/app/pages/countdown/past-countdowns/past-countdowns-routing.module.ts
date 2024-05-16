import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { PastCountdownsPage } from './past-countdowns.page';

const routes: Routes = [
  {
    path: '',
    component: PastCountdownsPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class PastCountdownsPageRoutingModule {}
