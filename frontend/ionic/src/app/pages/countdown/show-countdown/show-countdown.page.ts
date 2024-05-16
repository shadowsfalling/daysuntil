import { HttpClient } from '@angular/common/http';
import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { CountdownService } from 'src/app/services/countdown.service';

@Component({
  selector: 'app-show-countdown',
  templateUrl: './show-countdown.page.html',
  styleUrls: ['./show-countdown.page.scss'],
})
export class ShowCountdownPage implements OnInit {

  id: number = null!;
  title: string = '';
  datetime: Date = null!;
  countdownText: string = '';
  isOwner = true; // todo: check if user is really the owner

  constructor(private route: ActivatedRoute, private http: HttpClient, private countdownService: CountdownService) { }

  ngOnInit(): void {
    this.id = this.route.snapshot.params['id'];
    this.getCountdownDetails();
  }

  getCountdownDetails() {

    this.countdownService.getCountdownById(this.id)
    .subscribe({
      next: (data) => {
        this.title = data.title;
        this.datetime = new Date(data.datetime);
        this.updateCountdown();
        setInterval(() => this.updateCountdown(), 1000);
      },
      error: (error) => {
        console.error('Error loading countdown data', error);
      }
    });
  }

  updateCountdown() {
    const now = new Date().getTime();
    const countToDate = this.datetime.getTime();
    const difference = countToDate - now;

    const days = Math.floor(difference / (1000 * 60 * 60 * 24));
    const hours = Math.floor((difference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((difference % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((difference % (1000 * 60)) / 1000);

    this.countdownText = `${days} Tage ${hours} Stunden ${minutes} Minuten ${seconds} Sekunden`;
  }

}
