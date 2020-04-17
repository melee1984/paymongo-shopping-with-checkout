<template>
    <div class="container-fluid p-4">
        <div class="row" v-if="!paymentComplete">
            <div class="col-md-8" v-if="!isCheckout">
                <div class="row">
                    <div class="col-md-3 mb-3" v-for="product in productList">
                        <div class="card">
                            <div class="img">
                                <img :src="'http://checkout.test/products/'+product.image" clas="img-responsive p-2">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">
                                    <small>{{product.name}}</small>
                                    <div class="text-right price"><small>{{ product.price }} PHP</small> </div>
                                </h5>
                                <div class="text-right">
                                    <a href="javascript:void(0);" v-on:click="addCart(product)" class="pull-right btn btn-primary btn-block btn-sm">Add Cart</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 pl-2">
                <summary-component 
                    v-bind:productCartItem="productCartItem" 
                    v-on:continueCheckout="continueCheckout">
                </summary-component>
            </div>
            <div class="col-md-8" v-if="isCheckout">
                <checkout-component 
                    v-bind:productCartItem="productCartItem" 
                    v-on:cancelPayment="cancelPayment"
                    v-on:paymentSuccess="paymentSuccess"
                    >
                </checkout-component>             
            </div>
        </div>
        <div clas="row" v-if="paymentComplete">
            <h1>Payment Completed!</h1>
            <p>You have successfully paid!</p>
        </div>
    </div>
</template>

<script>
    
    import summary from "../components/cart/SummaryComponent.vue";
    import checkout from "../components/cart/CheckoutComponent.vue";
    
    export default {
        components: {
            'summary-component': summary,
            'checkout-component': checkout,
        },
        data () {
            return {
                error:{},
                productList: {},
                productCartItem: [],
                isCheckout: false,
                paymentComplete: false
            }
        },
        mounted() {
            // fetch getting product list in array
            this.getProductList();
            //
            console.log('Component mounted.')
        },
        methods: {
            getProductList: function () {

                var self = this;
                axios.get('/api/product/list').then(function (response) 
                {
                    self.productList = response.data;
                }).catch(function (error) {
                    console.log(error);
                });
            },
            addCart: function(product) {
                var self = this;
                self.productCartItem.push(product);
            },
            continueCheckout: function (checkout) {
                var self = this;
                self.isCheckout = checkout;
            },
            paymentSuccess() {
                var self = this;
                self.paymentComplete = true;
            },
            cancelPayment: function() {
                var self = this;
                self.isCheckout = false;
            },

        },

    }
</script>
