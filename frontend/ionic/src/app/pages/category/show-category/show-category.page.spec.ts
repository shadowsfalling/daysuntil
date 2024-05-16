import { ComponentFixture, TestBed } from '@angular/core/testing';
import { ShowCategoryPage } from './show-category.page';

describe('ShowCategoryPage', () => {
  let component: ShowCategoryPage;
  let fixture: ComponentFixture<ShowCategoryPage>;

  beforeEach(() => {
    fixture = TestBed.createComponent(ShowCategoryPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
