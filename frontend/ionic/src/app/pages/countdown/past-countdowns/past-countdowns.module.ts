import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { PastCountdownsPageRoutingModule } from './past-countdowns-routing.module';

import { PastCountdownsPage } from './past-countdowns.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    PastCountdownsPageRoutingModule
  ],
  declarations: [PastCountdownsPage]
})
export class PastCountdownsPageModule {}
