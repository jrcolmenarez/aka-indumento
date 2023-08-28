import { Injectable } from "@angular/core";
import {HttpClient, HttpHeaders, HttpRequest, HttpEvent } from '@angular/common/http';
import {Observable} from 'rxjs';
import { User } from "../models/user";
import { global } from "./global";

@Injectable()
export class UserService{
  public url: string;
	public identity:any;
	public token:any;

  constructor(
    public _http: HttpClient
  ){
    this.url=global.url;
  }

	register(user: User): Observable<any>{
		let json = JSON.stringify(user); //convertimos usuario en json
		let params = 'json='+json; //creamos la variable json
		console.log(params);
		let headers = new HttpHeaders().set('content-type','application/x-www-form-urlencoded');
		//peticion ajax
		return this._http.post(this.url+'user/register', params, {headers: headers});
	}

  signup(user: any, gettoken:any = null): Observable<any>{

    if (gettoken != null){
        user.gettoken = 'true';
    }
    let json = JSON.stringify(user);
    let params = 'json='+json;
    let headers = new HttpHeaders().set('content-type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url+'user/login', params, {headers: headers});
    }

  updateUser(token: any, user: any): Observable<any>{

    let json = JSON.stringify(user);
    let params = 'json='+json;
    let headers = new HttpHeaders().set('content-type','application/x-www-form-urlencoded')
                    .set('Authorization', token);
    return this._http.put(this.url+'user/update',params, {headers:headers});

  }

  getUser(id: any, token: any): Observable<any>{
		let headers = new HttpHeaders().set('content-type','application/json')
                    .set('Authorization', token);
		return this._http.get(this.url+'user/detail/'+id,{headers:headers});
	}

  /*
  upload(file: File, token: any = null): Observable<HttpEvent<any>> {
    const formData: FormData = new FormData();
    formData.append('file0', file);
    const req = new HttpRequest('POST', `${this.url}user/upload`, formData, {
      reportProgress: true,
      responseType: 'json'
    });
    const authReq = req.clone({ setHeaders: { Authorization: token } });
    return this._http.request(authReq);
  }*/

  getIdentity(){

    let identityString = localStorage.getItem('identity');
        if(identityString !== null){
          let identity = JSON.parse(identityString); // Parsea la cadena JSON
          if(identity && typeof identity !== "undefined"){
            this.identity = identity;
          }else{
            this.identity = null;
          }
        }else{
          this.identity = null;
        }
        //console.log(this.identity);
        return this.identity;

  }

  getToken(){
    let token = localStorage.getItem('token');

    if(token && token != "undefined"){
      this.token = token;
    }else{
      this.token = null;
    }
    return this.token;

  }



}
