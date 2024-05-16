import { Component, OnInit } from '@angular/core';
import { startCountdown } from 'src/app/helpers/countdown';
import { CountdownService } from 'src/app/services/countdown.service';

@Component({
  selector: 'app-past-countdowns',
  templateUrl: './past-countdowns.page.html',
  styleUrls: ['./past-countdowns.page.scss'],
})
export class PastCountdownsPage implements OnInit {


  countdowns: any[] = [];

  constructor(private countdownService: CountdownService) {}

  ngOnInit() {
    this.countdownService.getPastCountdowns().subscribe({
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
