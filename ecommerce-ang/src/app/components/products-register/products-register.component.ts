import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute, Params } from '@angular/router';
import { UserService } from '../../services/user.services';
import { Product } from 'src/app/models/product';
import { HttpEventType, HttpResponse } from '@angular/common/http';
import { global } from 'src/app/services/global';
import {ProductService} from '../../services/products.services';


@Component({
  selector: 'app-products-register',
  templateUrl: './products-register.component.html',
  styleUrls: ['./products-register.component.css'],
  providers: [UserService, ProductService]
})
export class ProductsRegisterComponent implements OnInit {

  public identity: any;
  public product: Product;
  public is_edit: boolean;
  public url: string;
  public token: string;
  public numberimag: number;
  selectedFiles?: FileList;
  currentFile?: File;
  progress1 = 0;
  progress2 = 0;
  progress3 = 0;
  message = '';

  constructor(
    private _userService: UserService,
    private _router: Router,
    private _productService: ProductService
  ){
    this.identity = this._userService.getIdentity();
    this.product=new Product(1,'','',1,1,'','','','','');
    this.is_edit=false;
    this.token = this._userService.getToken();
    this.url = global.url;
    this.numberimag = 1;
  }
  ngOnInit(): void {
    if (this.identity.role != 'ROLE_ADMIN' || this.identity.role == 'null'){
      this._router.navigate(['error']);
    }
    console.log(this.token);
  }

  selectFile(event: any): void {
    this.selectedFiles = event.target.files;
    this.upload();
  }

  upload(): void {
    this.progress1 = 0;
    this.progress2 = 0;
    this.progress3 = 0;

    if (this.selectedFiles) {
      const file: File | null = this.selectedFiles.item(0);

      if (file) {
        this.currentFile = file;

        this._productService.upload(this.currentFile, this.token).subscribe({
          next: (event: any) => {
            if (event.type === HttpEventType.UploadProgress) {
              if (this.numberimag == 1){
                this.progress1 = Math.round((100 * event.loaded) / event.total);
              }else if (this.numberimag == 2){
                this.progress2 = Math.round((100 * event.loaded) / event.total);
              }else if (this.numberimag == 3){
                this.progress3 = Math.round((100 * event.loaded) / event.total);
              }
              this.progress1 = Math.round((100 * event.loaded) / event.total);
            } else if (event instanceof HttpResponse) {
              this.message = event.body.message;
              if (this.numberimag == 1){
                this.product.image1=event.body.image;
              }else if (this.numberimag == 2){
                this.product.image2=event.body.image;
              }else if (this.numberimag == 3){
                this.product.image3=event.body.image;
              }
              this.numberimag = this.numberimag + 1;
              console.log(this.product.image1);
            }
          },
          error: (err: any) => {
            console.log(err);
            this.progress1 = 0;

            if (err.error && err.error.message) {
              this.message = err.error.message;
            } else {
              this.message = 'No se logro subir la imagen!';
            }

            this.currentFile = undefined;
          },
        });
      }

      this.selectedFiles = undefined;
    }
  }

  onSubmit(form: any){
    console.log(this.product);
  }

}
