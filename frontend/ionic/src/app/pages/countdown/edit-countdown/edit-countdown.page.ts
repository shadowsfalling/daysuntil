import { Component, OnInit } from '@angular/core';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { CountdownService } from 'src/app/services/countdown.service';
import { NavController } from '@ionic/angular';

@Component({
  selector: 'app-edit-countdown',
  templateUrl: './edit-countdown.page.html',
  styleUrls: ['./edit-countdown.page.scss'],
})
export class EditCountdownPage implements OnInit {
  countdownForm: FormGroup;
  countdownId: number | undefined;

  constructor(
    private formBuilder: FormBuilder,
    private countdownService: CountdownService,
    private route: ActivatedRoute,
    private router: Router,
    private navCtrl: NavController
  ) {
    this.countdownForm = this.formBuilder.group({
      title: ['', Validators.required],
      datetime: ['', Validators.required],
      is_public: [false, Validators.required],
      category_id: [null]
    });
  }

  ngOnInit() {
    const id = this.route.snapshot.paramMap.get('id');
    if (id) {

      // todo: validieren
      this.countdownId = parseFloat(id);

      this.countdownService.getCountdownById(parseInt(id)).subscribe(data => {
        this.countdownForm.setValue({
          title: data.title,
          datetime: data.datetime,
          is_public: 1,
          category_id: 1
        });
      });
    }
  }

  submitForm() {
    const formValue = this.countdownForm.value;
    if (this.countdownId) {
      this.countdownService.updateCountdown(this.countdownId!, formValue).subscribe(() => {
        this.navCtrl.back();
      });
    } else {
      this.countdownService.createCountdown(formValue).subscribe(() => {
        this.navCtrl.back();
      });
    }
  }
}