import { Component } from '@angular/core';

@Component({
  selector: 'app-error',
  templateUrl: './error.component.html',
  styleUrls: ['./error.component.css']
})
export class ErrorComponent {
  public page_title: string;

  constructor(){
    this.page_title="Pagina No encontrada";
  }
}
