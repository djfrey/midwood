<template>
    <div class="modal fade" tabindex="-1" role="dialog" id="stateSelect">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Select states/regions</h5>
                        <button type="button" class="close" v-on:click="close()" aria-label="Close">
                            <i class="fas fa-times"></i>
                        </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div v-for="(s, k) in states" class="col-4">
                                <div class="custom-control custom-checkbox">
                                    <input v-model="staff.states" type="checkbox" :value="s.abbreviation" class="custom-control-input" :id="'state_'+k">
                                    <label class="custom-control-label"  :for="'state_'+k">{{s.name}}</label>
                                </div>
                            </div>
                        </div>
                    </div>                    
                </div>                       
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" v-on:click="save()"><i class="fas fa-check"></i> Update</button>
                    <button type="button" class="btn btn-secondary" v-on:click="close()">Close</button>                
                </div>                
            </div>            
        </div>
    </div>
</template>
<script>
module.exports = {	
    props: {
        data: {
            type: Object,
            required: true
		}
	},
    data () {
        return {
            staff: {},
            states: [
                {
                    "name": "Alabama",
                    "abbreviation": "AL"
                },
                {
                    "name": "Alaska",
                    "abbreviation": "AK"
                },                
                {
                    "name": "Arizona",
                    "abbreviation": "AZ"
                },
                {
                    "name": "Arkansas",
                    "abbreviation": "AR"
                },
                {
                    "name": "California",
                    "abbreviation": "CA"
                },
                {
                    "name": "Colorado",
                    "abbreviation": "CO"
                },
                {
                    "name": "Connecticut",
                    "abbreviation": "CT"
                },
                {
                    "name": "Delaware",
                    "abbreviation": "DE"
                },
                {
                    "name": "District Of Columbia",
                    "abbreviation": "DC"
                },            
                {
                    "name": "Florida",
                    "abbreviation": "FL"
                },
                {
                    "name": "Georgia",
                    "abbreviation": "GA"
                },
                {
                    "name": "Hawaii",
                    "abbreviation": "HI"
                },
                {
                    "name": "Idaho",
                    "abbreviation": "ID"
                },
                {
                    "name": "Illinois",
                    "abbreviation": "IL"
                },
                {
                    "name": "Indiana",
                    "abbreviation": "IN"
                },
                {
                    "name": "Iowa",
                    "abbreviation": "IA"
                },
                {
                    "name": "Kansas",
                    "abbreviation": "KS"
                },
                {
                    "name": "Kentucky",
                    "abbreviation": "KY"
                },
                {
                    "name": "Louisiana",
                    "abbreviation": "LA"
                },
                {
                    "name": "Maine",
                    "abbreviation": "ME"
                },                
                {
                    "name": "Maryland",
                    "abbreviation": "MD"
                },
                {
                    "name": "Massachusetts",
                    "abbreviation": "MA"
                },
                {
                    "name": "Eastern Massachusetts",
                    "abbreviation": "E MA"
                },
                {
                    "name": "Western Massachusetts",
                    "abbreviation": "W MA"
                },
                {
                    "name": "Michigan",
                    "abbreviation": "MI"
                },
                {
                    "name": "Minnesota",
                    "abbreviation": "MN"
                },
                {
                    "name": "Mississippi",
                    "abbreviation": "MS"
                },
                {
                    "name": "Missouri",
                    "abbreviation": "MO"
                },
                {
                    "name": "Montana",
                    "abbreviation": "MT"
                },
                {
                    "name": "Nebraska",
                    "abbreviation": "NE"
                },
                {
                    "name": "Nevada",
                    "abbreviation": "NV"
                },
                {
                    "name": "New Hampshire",
                    "abbreviation": "NH"
                },
                {
                    "name": "New Jersey",
                    "abbreviation": "NJ"
                },
                {
                    "name": "New Mexico",
                    "abbreviation": "NM"
                },
                {
                    "name": "New York",
                    "abbreviation": "NY"
                },
                {
                    "name": "North Carolina",
                    "abbreviation": "NC"
                },
                {
                    "name": "North Dakota",
                    "abbreviation": "ND"
                },               
                {
                    "name": "Ohio",
                    "abbreviation": "OH"
                },
                {
                    "name": "Oklahoma",
                    "abbreviation": "OK"
                },
                {
                    "name": "Oregon",
                    "abbreviation": "OR"
                },                
                {
                    "name": "Pennsylvania",
                    "abbreviation": "PA"
                },
                {
                    "name": "Eastern Pennsylvania",
                    "abbreviation": "E PA"
                },
                {
                    "name": "Western Pennsylvania",
                    "abbreviation": "W PA"
                },                
                {
                    "name": "Rhode Island",
                    "abbreviation": "RI"
                },
                {
                    "name": "South Carolina",
                    "abbreviation": "SC"
                },
                {
                    "name": "South Dakota",
                    "abbreviation": "SD"
                },
                {
                    "name": "Tennessee",
                    "abbreviation": "TN"
                },
                {
                    "name": "Texas",
                    "abbreviation": "TX"
                },
                {
                    "name": "Utah",
                    "abbreviation": "UT"
                },
                {
                    "name": "Vermont",
                    "abbreviation": "VT"
                },             
                {
                    "name": "Virginia",
                    "abbreviation": "VA"
                },
                {
                    "name": "Washington",
                    "abbreviation": "WA"
                },
                {
                    "name": "West Virginia",
                    "abbreviation": "WV"
                },
                {
                    "name": "Wisconsin",
                    "abbreviation": "WI"
                },
                {
                    "name": "Wyoming",
                    "abbreviation": "WY"
                }
            ]
        }
    },
    methods: {     
        save: function() {
            var _this = this;            
            this.$parent.tempUser.sales.find(s => {
                return s.staffId == _this.staff.staffId;
            }).states = _this.staff.states.sort();
            this.close();
        },
        close: function() {
            $('#stateSelect').modal('hide');          
        }
    },
    watch: {
        data: function(newVal, oldVal) {
            this.staff = Object.assign({}, newVal);
        }
    }
}
</script>
<style>
    .modal-body {
        padding-left: 0;
        padding-right: 0;
        padding-bottom: 0.5rem;
    }    
</style>