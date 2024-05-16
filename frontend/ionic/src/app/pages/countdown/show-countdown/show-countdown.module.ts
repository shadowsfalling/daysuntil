import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { ShowCountdownPageRoutingModule } from './show-countdown-routing.module';

import { ShowCountdownPage } from './show-countdown.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    ShowCountdownPageRoutingModule
  ],
  declarations: [ShowCountdownPage]
})
export class ShowCountdownPageModule {}
