import { Product } from './../models/product';
import { Injectable } from "@angular/core";
import {HttpClient, HttpHeaders, HttpRequest, HttpEvent } from '@angular/common/http';
import {Observable} from 'rxjs';
import { User } from "../models/user";
import { global } from "./global";

@Injectable()
export class ProductService{

  public url: string;

  constructor(
    public _http: HttpClient
  ){
    this.url=global.url;
  }

  registerProduct(prod: Product, token: any): Observable<any>{
    //limpiamos el campo
    prod.description =  global.htmlEntities(prod.description);
    let json = JSON.stringify(prod);
    let params = 'json='+json;
    console.log(params);
    let headers = new HttpHeaders().set('content-type','application/x-www-form-urlencoded')
                    .set('Authorization', token);
    return this._http.post(this.url+'product',params, {headers:headers});
  }

  upload(file: File, token: any = null): Observable<HttpEvent<any>> {
    const formData: FormData = new FormData();
    formData.append('file0', file);
    const req = new HttpRequest('POST', `${this.url}product/upload`, formData, {
      reportProgress: true,
      responseType: 'json'
    });
    const authReq = req.clone({ setHeaders: { Authorization: token } });
    return this._http.request(authReq);
  }

  updateProd(product: Product, token: any, id: any): Observable<any>{
    product.description =  global.htmlEntities(product.description);
    let json = JSON.stringify(product);
    let params = 'json='+json;
    let headers = new HttpHeaders().set('content-type','application/x-www-form-urlencoded')
                    .set('Authorization', token);
    return this._http.put(this.url+'product/'+id,params, {headers:headers});
  }

  getProduct(id: any): Observable<any>{
		let headers = new HttpHeaders().set('content-type','application/json');
		return this._http.get(this.url+'product/'+id,{headers:headers});
	}

}
