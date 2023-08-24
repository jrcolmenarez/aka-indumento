import { Component, OnInit, DoCheck } from '@angular/core';
import { UserService } from './services/user.services';
import { global } from 'src/app/services/global';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css'],
  providers: [UserService]
})
export class AppComponent {
  title = 'ecommerce-ang';
  public identity:any;
  public token:any;
  public url:any;

  constructor(
    private _userService: UserService
  ){
    this.url=global.url;
  }

  ngDoCheck(){
    this.loadUser();
  }

  loadUser(){
    this.identity= this._userService.getIdentity();
    this.token=this._userService.getToken();
}


}
