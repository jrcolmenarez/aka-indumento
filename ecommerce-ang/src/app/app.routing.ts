import { ModuleWithProviders } from '@angular/core';
import {Routes, RouterModule} from '@angular/router';

//importar componentes
import { LoginComponent } from './components/login/login.component';
import { RegisterComponent } from './components/register/register.component';
import { HomeComponent } from './components/home/home.component';
import {UserEditComponent} from './components/user-edit/user-edit.component';
import { ErrorComponent } from './components/error/error.component';

const appRoutes: Routes = [
  {path: '', component: HomeComponent},
  {path:'inicio', component: HomeComponent},
  {path: 'login', component: LoginComponent},
  {path: 'registro', component: RegisterComponent },
  {path: 'userperf/:id', component: UserEditComponent},
  {path:'logout/:sure', component: LoginComponent},
  {path: '**', component: ErrorComponent}
];

export const appRoutingProviders: any[] = [];
export const routing: ModuleWithProviders<any> = RouterModule.forRoot(appRoutes);
