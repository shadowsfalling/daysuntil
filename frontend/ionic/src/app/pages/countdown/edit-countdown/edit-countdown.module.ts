import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { EditCountdownPageRoutingModule } from './edit-countdown-routing.module';

import { EditCountdownPage } from './edit-countdown.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    EditCountdownPageRoutingModule,
    ReactiveFormsModule
  ],
  declarations: [EditCountdownPage]
})
export class EditCountdownPageModule {}
