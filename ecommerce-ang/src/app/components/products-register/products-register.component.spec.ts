import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ProductsRegisterComponent } from './products-register.component';

describe('ProductsRegisterComponent', () => {
  let component: ProductsRegisterComponent;
  let fixture: ComponentFixture<ProductsRegisterComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ ProductsRegisterComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(ProductsRegisterComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
