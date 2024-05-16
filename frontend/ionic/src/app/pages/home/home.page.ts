import { Component, OnInit } from '@angular/core';
import { startCountdown } from 'src/app/helpers/countdown';
import { CountdownService } from 'src/app/services/countdown.service';

@Component({
  selector: 'app-home',
  templateUrl: './home.page.html',
  styleUrls: ['./home.page.scss'],
})
export class HomePage implements OnInit {

  countdowns: any[] = [];

  constructor(private countdownService: CountdownService) {}

  ngOnInit() {

  }

  ionViewDidEnter() {
    this.countdownService.getCountdowns().subscribe({
      next: (data) => {
        this.countdowns = data.map((cd: any) => ({
          ...cd,
          countdownDisplay: 'Warte auf Start...'
        }));
        this.countdowns.forEach(cd => {
          startCountdown(cd.datetime, (display: string) => {
            cd.countdownDisplay = display;
          });
        });
      },
      error: (error) => console.error('Error loading countdowns', error)
    });
  }

}
