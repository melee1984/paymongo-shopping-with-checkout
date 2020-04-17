<template>
	<div id="checkout">
		<aside class="col-sm-12 col-md-12 col-lg-12 col-12">
			<article class="">
				<div class="card-body p-3">
				<div class="tab-content">
				<div class="tab-pane fade show active" id="nav-tab-card">
					<h5 class="mb-4">Enter your Card Details</h5> 

          <div class="text-danger">
             <ul>
               <li v-for="item in errorMessage">{{item}}</li>
             </ul>
          </div>

  				<form role="form">
					<div class="form-group">
						<label for="cardName">Cardholder's Name</label>
						<input ref="cardNameInput" type="text" class="form-control" name="cardName" id="cardName" v-model="cardName" placeholder="eg. Juan Dela Cruz" :data-error="(cardErrors.cardName)?true:false">
						<div v-if="cardErrors.cardName" class="text-danger">
               	<small>{{ cardErrors.cardName }}</small>
            </div>
					</div> <!-- form-group.// -->
					<div class="form-group">
						<label for="cardNumber">Card number</label>
						<div class="input-group number">
							<input ref="cardNumInput" :data-error="(cardErrors.cardNumber)?true:false" v-model="cardNumber" type="tel" class="form-control" placeholder="#### #### #### ####" v-cardformat:formatCardNumber >
							<div class="input-group-append">
							    <span class="input-group-text" id="basic-addon2">
							    	<i :class="cardBrandClass"></i>
				          </span>
							</div>
					  	</div>
              <div v-if="cardErrors.cardNumber" class="text-danger">
               	<small>{{ cardErrors.cardNumber }}</small>
              </div>
					   </div> <!-- form-group.// -->
					<div class="row">
					    <div class="col-sm-8">
					        <div class="form-group expiration">
					         <label><span class="hidden-xs">Expiration</span> </label>
					        	<input  ref="cardExpInput" id="card-exp" :data-error="(cardErrors.cardExpiry)?true:false" v-model="cardExpiry" maxlength="10" class="form-control" v-cardformat:formatCardExpiry placeholder="MM/YY">
					        	  <div v-if="cardErrors.cardExpiry" class="text-danger">
			                   <small>{{ cardErrors.cardExpiry }}</small>
			                </div>
					        </div>
					    </div>
					    <div class="col-sm-4">
					        <div class="form-group cvc">
					            <label data-toggle="tooltip" title="" data-original-title="3 digits code on back side of the card">CVV <i class="fa fa-question-circle"></i></label>
					            <input ref="cardCvcInput" :data-error="(cardErrors.cardCvc)?true:false" v-model="cardCvc" class="form-control" v-cardformat:formatCardCVC>
					             <div v-if="cardErrors.cardCvc" class="text-danger">
				                    <small>{{ cardErrors.cardCvc }}</small>
				                 </div>
					        </div> <!-- form-group.// -->
					    </div>
					</div> <!-- row.// -->
					<button class="subscribe btn btn-primary btn-block" type="button" v-on:click="validate">
          <span class="spinner-border spinner-border-sm" v-bind:class="{hide: !completePayment}" v-model="completePayment"></span> 
              Complete Payment
          </button>
          <a href="javascript:void(0);" v-on:click="$emit('cancelPayment')" class="btn btn-secondary btn-block">Cancel</a>
					</form>
  
  <br>
  <br>
          <div class="text-info">
              4123450131000508 <br>
              4242424242424242 <br>
              5454545454545454 <br>
              4917610000000000
          </div>
				</div> <!-- tab-pane.// -->
				</div> <!-- tab-content .// -->
				</div> <!-- card-body.// -->
			</article> <!-- card.// -->
		</aside> <!-- col.// -->
	</div>
</template>

<script>
	import VueCardFormat from 'vue-credit-card-validation';
	import Vue from 'vue';

	Vue.use(VueCardFormat);

    export default {
    	name: 'payment',
      props: ['productCartItem','paymentSuccess'],
      components: {
	      VueCardFormat
	    },
    	data() {
            return {
              errorMessage: [],
            	cardName: null,
				      cardNumber: null,
		          cardExpiry: null,
		          cardCvc: null,
		          cardPostal: null,
		          cardErrors: {},
		          cardBrand: null,
		          $cardFormat: Vue.prototype.$cardFormat,
              completePayment: false
            }
        },
        computed: {
          cardBrandClass() {
	          return this.getBrandClass(this.cardBrand);
          },
      },
		  created() {
			  	console.log('Created Instance CreditCard');
        },

        methods: {
          validate: function(){
            // init

            var self = this;

            this.cardErrors = {};
            this.completePayment = true;

            if(!this.cardName){
              this.cardErrors.cardName = "Required Cardholder name.";
              this.completePayment = false;
            }; 
            // validate card number
            if(!this.$cardFormat.validateCardNumber(this.cardNumber)){
              this.cardErrors.cardNumber = "Invalid Credit Card Number.";
              this.completePayment = false;
            };
            // validate card expiry
            if (!this.$cardFormat.validateCardExpiry(this.cardExpiry)) {
              this.cardErrors.cardExpiry = "Invalid Expiration Date.";
              this.completePayment = false;
            };
            // validate card CVC
            if (!this.$cardFormat.validateCardCVC(this.cardCvc)) {
              this.cardErrors.cardCvc = "Invalid CVC.";
              this.completePayment = false;
            };
            if (this.completePayment) {
                let item = [];
                this.errorMessage = [];

                this.productCartItem.forEach(e => {
                    item.push(e);
                });

                axios.post('/api/cc-process', {
                    name: this.cardName,
                    ccnumber: this.cardNumber,
                    expiry: this.cardExpiry,
                    cvc: this.cardCvc,
                    basket: item
                }).then(function (response) {
                    console.log('Here');
                    self.completePayment = true;
                    self.$emit('paymentSuccess');
                }).catch((errors) => {
                  console.log(errors);

                    if (errors.response.status == 500) {
                        this.errorMessage.push(errors.response.data.message); 
                    }
                    else { 
                        this.errorMessage.push(errors.response.data.message); 
                    }
                   
                    self.completePayment = false; 

                });
            }
          },
          reset: function(){
            this.cardErrors = {};
            this.cardName = null;
            this.cardNumber = null;
            this.cardExpiry = null;
            this.cardCvc = null;
            this.cardPostal = null;
            this.$refs.cardNameInput.focus();
          },
          prefill: function(ccNum){
            this.cardNumber = ccNum;
            this.$cardFormat.setCardType({
              currentTarget : this.$refs.cardNumInput,
              value: ccNum
            });
          },
          getBrandClass: function (brand) {
            let icon = '';
            brand = brand || 'unknown';
            let cardBrandToClass = {
              'visa': 'fab fa-cc-visa',
              'mastercard': 'fab fa-cc-mastercard',
              'amex': 'fab fa-cc-amex',
              'discover': 'fab fa-cc-discover',
              'diners': 'fab fa-cc-diners-club',
              'jcb': 'fab fa-cc-jcb',
              'unknown': 'fa fa-credit-card',
            };
            if (cardBrandToClass[brand]) {
              icon = cardBrandToClass[brand];
            };

            return icon;
          },
      },
      watch: {
        // handle auto-focus to next field
        // Note: since CVC can be 3 OR 4 digits we don't watch it
        cardNumber: function(val){
          if(this.$cardFormat.validateCardNumber(val)){
            this.$refs.cardExpInput.focus();
          }
        },
        cardExpiry: function (val) {
          if (this.$cardFormat.validateCardExpiry(val)) {
            this.$refs.cardCvcInput.focus();
          }
        }
      },
      mounted(){
        this.$refs.cardNameInput.focus();
      } 
  }

</script>
