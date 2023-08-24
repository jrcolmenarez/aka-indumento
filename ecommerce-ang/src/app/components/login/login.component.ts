import { Component, OnInit } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { User } from './../../models/user';
import { Router, ActivatedRoute, Params } from '@angular/router';
import { UserService } from '../../services/user.services';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css'],
  providers: [UserService]
})
export class LoginComponent implements OnInit {

  public page_title: string;
  public user: User;
  public status: string;
  public token: string;
  public identity: string;

  constructor(
    private _userService: UserService,
    private _router: Router,
    private _route: ActivatedRoute,
  ){
    this.page_title="Logeo";
    this.user = new User(1,'','','','','','','','','ROLE_USER');
    this.status='';
    this.token='';
    this.identity='';
  }

  ngOnInit(): void {
    this.logout();
  }
  onSubmit(form: any){
    this._userService.signup(this.user).subscribe(
      response =>{
        if (response.status == 'error'){
          this.status = 'error';
        }
        if (response.status != 'error'){
          this.status = 'success';
          this.token=response;
          //Llamo de nuevo la funcion pero que me devuelva los datos del usuario
          this._userService.signup(this.user, true).subscribe(
                response=>{
          //DEVUELVE TOKKEN
                      this.identity = response;
                      //PERSISTIR DATOS USUARIO, GUARDANDO PARA GUARDAR SESION
                      //console.log(this.token);
                      //console.log(this.identity);
                      localStorage.setItem('token', this.token);
                      localStorage.setItem('identity', JSON.stringify(this.identity));
                      //REDIRECCIONAN A INICIO
                      this._router.navigate(['inicio']);
                },
                error =>{
                  this.status='error';
                  console.log(error);
                });
          }
      },error=>{
         console.log(error.status);
         console.log(error);
      });
  }

  logout(){

    this._route.params.subscribe(

      params =>{
        let logout = + params['sure'];
        if(logout == 1){
            localStorage.removeItem('identity');
            localStorage.removeItem('token');
            console.log("cerre sesion");
            this.identity = '';
            this.token = '';
            this._router.navigate(['inicio']);
        }
      });
  }

}
