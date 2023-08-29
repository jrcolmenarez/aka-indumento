import { Product } from './../../models/product';
import { Component, OnInit, ModuleWithProviders } from '@angular/core';
import { Router, ActivatedRoute, Params } from '@angular/router';
import { UserService } from '../../services/user.services';
import { HttpEventType, HttpResponse } from '@angular/common/http';
import { global } from 'src/app/services/global';
import {ProductService} from '../../services/products.services';

@Component({
  selector: 'app-product-edit',
  templateUrl: '../products-register/products-register.component.html',
  styleUrls: ['./product-edit.component.css'],
  providers: [UserService, ProductService]
})
export class ProductEditComponent implements OnInit {

  public identity: any;
  public product: Product;
  public is_edit: boolean;
  public url: string;
  public token: string;
  public numberimag: string;
  public id_prod: number;
  selectedFiles?: FileList;
  currentFile?: File;
  progress1 = 0;
  progress2 = 0;
  progress3 = 0;
  message = '';

  constructor(
    private _userService: UserService,
    private _router: Router,
    private _productService: ProductService,
    private _route: ActivatedRoute
  ){
    this.identity = this._userService.getIdentity();
    this.product=new Product(1,'','',1,1,'','','','',this.identity.sub);
    this.is_edit=true;
    this.token = this._userService.getToken();
    this.url = global.url;
    this.numberimag = '';
    this.id_prod = 0;
  }

  ngOnInit(): void {
    if (this.identity.role != 'ROLE_ADMIN' || this.identity.role == 'null'){
      this._router.navigate(['error']);
    }else{
      this.getProduct();
    }
  }

  getProduct(){
    this._route.params.subscribe(
      params => {
        this.id_prod = +params['id'];
        this._productService.getProduct(this.id_prod).subscribe({
          next: data=>{
            if (data.status =='success'){
              this.product = data.product;
            }else{
              this._router.navigate(['inicio']);
            }
          }, error: error=>console.log('No se consiguio producto')
        });
      });
  }

  selectFile(event: any): void {
    this.selectedFiles = event.target.files;
    this.numberimag =event.target.id;
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
              if (this.numberimag == 'image1'){
                this.progress1 = Math.round((100 * event.loaded) / event.total);
              }else if (this.numberimag == 'image2'){
                this.progress2 = Math.round((100 * event.loaded) / event.total);
              }else if (this.numberimag == 'image3'){
                this.progress3 = Math.round((100 * event.loaded) / event.total);
              }
              //this.progress1 = Math.round((100 * event.loaded) / event.total);
            } else if (event instanceof HttpResponse) {
              this.message = event.body.message;
              if (this.numberimag == 'image1'){
                this.product.image1=event.body.image;
              }else if (this.numberimag == 'image2'){
                this.product.image2=event.body.image;
              }else if (this.numberimag == 'image3'){
                this.product.image3=event.body.image;
              }
              console.log(this.numberimag);
              console.log(event.body.image);
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
    this._productService.updateProd(this.product, this.token, this.id_prod).subscribe(
      response => {
         this._router.navigate(['inicio']);
      }, error => {
        console.log(<any>error);
      }
      );
  }

}
