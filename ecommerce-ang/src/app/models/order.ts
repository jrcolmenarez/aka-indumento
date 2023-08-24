export class Order{
  constructor(
    public id: number,
    public user_id: number,
    public state: string,
    public address_order: string,
    public payment_methods: string,
    public total: number
  ){}
}
