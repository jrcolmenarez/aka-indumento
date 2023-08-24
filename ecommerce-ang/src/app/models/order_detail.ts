export class Order_detail{
  constructor(
    public id: number,
    public order_id: number,
    public product_id: number,
    public amount: number,
    public price_unit: number,
    public total: number
  ){}
}
