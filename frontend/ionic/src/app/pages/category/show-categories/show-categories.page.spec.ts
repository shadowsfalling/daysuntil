import { ComponentFixture, TestBed } from '@angular/core/testing';
import { ShowCategoriesPage } from './show-categories.page';

describe('ShowCategoriesPage', () => {
  let component: ShowCategoriesPage;
  let fixture: ComponentFixture<ShowCategoriesPage>;

  beforeEach(() => {
    fixture = TestBed.createComponent(ShowCategoriesPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
