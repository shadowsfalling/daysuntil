import { ComponentFixture, TestBed } from '@angular/core/testing';
import { EditCountdownPage } from './edit-countdown.page';

describe('EditCountdownPage', () => {
  let component: EditCountdownPage;
  let fixture: ComponentFixture<EditCountdownPage>;

  beforeEach(() => {
    fixture = TestBed.createComponent(EditCountdownPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
