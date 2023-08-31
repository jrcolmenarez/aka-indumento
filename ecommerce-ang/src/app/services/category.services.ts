import { Category } from './../models/category';
import { Injectable } from "@angular/core";
import {HttpClient, HttpHeaders, HttpRequest, HttpEvent } from '@angular/common/http';
import {Observable} from 'rxjs';
import { global } from "./global";

@Injectable()
export class CategoryServices{
  public url: string;

  constructor(
    public _http: HttpClient
  ){
    this.url=global.url;
  }

  registerCAt(category: Category, token: any): Observable<any>{

    let json = JSON.stringify(category); //convertimos usuario en json
		let params = 'json='+json; //creamos la variable json
		let headers = new HttpHeaders().set('content-type','application/x-www-form-urlencoded')
                                    .set('Authorization', token);;
		//peticion ajax
		return this._http.post(this.url+'category', params, {headers: headers});

  }

  getCategorybyId(id: any): Observable<any>{
    let headers = new HttpHeaders().set('content-type','application/json');
    return this._http.get(this.url+'category/'+id,{headers:headers});
  }

  getAllCategories(): Observable<any>{
    let headers = new HttpHeaders().set('content-type','application/json');
    return this._http.get(this.url+'category',{headers:headers});
  }

  updateCategory(category: Category, token: any): Observable<any>{
    let json = JSON.stringify(category); //convertimos usuario en json
		let params = 'json='+json; //creamos la variable json
		console.log(params);
		let headers = new HttpHeaders().set('content-type','application/x-www-form-urlencoded')
                                    .set('Authorization', token);;
		//peticion ajax
		return this._http.put(this.url+'category/'+category.id, params, {headers: headers});
  }

  upload(file: File, token: any = null): Observable<HttpEvent<any>> {
    const formData: FormData = new FormData();
    formData.append('file0', file);
    const req = new HttpRequest('POST', `${this.url}category/upload`, formData, {
      reportProgress: true,
      responseType: 'json'
    });
    const authReq = req.clone({ setHeaders: { Authorization: token } });
    return this._http.request(authReq);
  }

}
