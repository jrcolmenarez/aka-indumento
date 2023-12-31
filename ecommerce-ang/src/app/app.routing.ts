import { AppComponent } from './app.component';
import { ModuleWithProviders, Component } from '@angular/core';
import {Routes, RouterModule} from '@angular/router';

//importar componentes
import { LoginComponent } from './components/login/login.component';
import { RegisterComponent } from './components/register/register.component';
import { HomeComponent } from './components/home/home.component';
import {UserEditComponent} from './components/user-edit/user-edit.component';
import { ErrorComponent } from './components/error/error.component';
import {ProductsRegisterComponent} from './components/products-register/products-register.component';
import {ProductEditComponent} from './components/product-edit/product-edit.component';
import {CategoryRegisterComponent} from './components/category-register/category-register.component';
import {CategoryEditComponent} from './components/category-edit/category-edit.component'


const appRoutes: Routes = [
  {path: '', component: HomeComponent},
  {path:'inicio', component: HomeComponent},
  {path: 'login', component: LoginComponent},
  {path: 'registro', component: RegisterComponent },
  {path: 'userpedit/:id', component: UserEditComponent},
  {path:'logout/:sure', component: LoginComponent},
  {path: 'registroprod', component: ProductsRegisterComponent},
  {path: 'editproduct/:id', component: ProductEditComponent},
  {path: 'registrocateogria', component: CategoryRegisterComponent},
  {path: 'editcategoria', component: CategoryEditComponent},
  {path: '**', component: ErrorComponent}
];

export const appRoutingProviders: any[] = [];
export const routing: ModuleWithProviders<any> = RouterModule.forRoot(appRoutes);
