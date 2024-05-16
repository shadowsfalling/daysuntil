import { ComponentFixture, TestBed } from '@angular/core/testing';
import { PastCountdownsPage } from './past-countdowns.page';

describe('PastCountdownsPage', () => {
  let component: PastCountdownsPage;
  let fixture: ComponentFixture<PastCountdownsPage>;

  beforeEach(() => {
    fixture = TestBed.createComponent(PastCountdownsPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
