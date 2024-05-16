import { ComponentFixture, TestBed } from '@angular/core/testing';
import { ShowCountdownPage } from './show-countdown.page';

describe('ShowCountdownPage', () => {
  let component: ShowCountdownPage;
  let fixture: ComponentFixture<ShowCountdownPage>;

  beforeEach(() => {
    fixture = TestBed.createComponent(ShowCountdownPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
