import { NgModule } from '@angular/core';
import { PreloadAllModules, RouterModule, Routes } from '@angular/router';
import { AuthGuard } from './guard/auth.guard';

const routes: Routes = [
  {
    path: '',
    redirectTo: 'home',
    pathMatch: 'full'
  },
  {
    path: 'home',
    loadChildren: () => import('./pages/home/home.module').then( m => m.HomePageModule),
    canActivate: [AuthGuard]
  },
  {
    path: 'countdowns/add',
    loadChildren: () => import('./pages/countdown/edit-countdown/edit-countdown.module').then( m => m.EditCountdownPageModule),
    canActivate: [AuthGuard]
  },
  {
    path: 'countdown/:id/edit',
    loadChildren: () => import('./pages/countdown/edit-countdown/edit-countdown.module').then( m => m.EditCountdownPageModule),
    canActivate: [AuthGuard]
  },
  {
    path: 'countdown/:id',
    loadChildren: () => import('./pages/countdown/show-countdown/show-countdown.module').then( m => m.ShowCountdownPageModule)
  },
  {
    path: 'edit-category',
    loadChildren: () => import('./pages/category/edit-category/edit-category.module').then( m => m.EditCategoryPageModule),
    canActivate: [AuthGuard]
  },
  {
    path: 'category/:id',
    loadChildren: () => import('./pages/category/show-category/show-category.module').then( m => m.ShowCategoryPageModule),
    canActivate: [AuthGuard]
  },
  {
    path: 'categories',
    loadChildren: () => import('./pages/category/show-categories/show-categories.module').then( m => m.ShowCategoriesPageModule),
    canActivate: [AuthGuard]
  },
  {
    path: 'login',
    loadChildren: () => import('./pages/login/login.module').then( m => m.LoginPageModule)
  },
  {
    path: 'register',
    loadChildren: () => import('./pages/register/register.module').then( m => m.RegisterPageModule)
  },
  {
    path: 'countdowns/past',
    loadChildren: () => import('./pages/countdown/past-countdowns/past-countdowns.module').then( m => m.PastCountdownsPageModule)
  }
];

@NgModule({
  imports: [
    RouterModule.forRoot(routes, { preloadingStrategy: PreloadAllModules })
  ],
  exports: [RouterModule]
})
export class AppRoutingModule {}
