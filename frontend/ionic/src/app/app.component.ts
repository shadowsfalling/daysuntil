import { Component } from '@angular/core';
@Component({
  selector: 'app-root',
  templateUrl: 'app.component.html',
  styleUrls: ['app.component.scss'],
})
export class AppComponent {
  public appPages = [
    { title: 'Übersicht', url: '/home', icon: 'mail' },
    { title: 'Vergangene Termine', url: '/countdowns/past', icon: 'mail' },
    { title: 'Kategorien', url: '/categories', icon: 'mail' },    
  ];
  
  constructor() {}
}
