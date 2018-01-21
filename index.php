<html>
<head>
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<link rel="manifest" href="/manifest.json">
<link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
<meta name="theme-color" content="#ffffff">

<title>Calc</title>
<link rel="stylesheet" href="css/bootstrap.min.css" />
<link rel="stylesheet" href="css/datepicker.min.css" />
<link rel="stylesheet" href="css/styles.css" />
</head>
<body>

<div class="dp"></div>

<div class="container-fluid">
	<div class="calc row-fluid">
		<div class="col-xs-12 col-sm-12 col-md-6">
			<label class="control-label">Оплата за день</label>
			<input type="number" size="5" id="price" class="form-control input-sm" />
			<hr />
			<table class="periods table table-striped" id="periods">
				<tr v-if="showHead" class="calc-head">
					<td>Период</td>
					<td>Дней</td>
					<td>За день</td>
					<td>За период</td>
				</tr>
				<tr v-for="(item, i) in items" class="calc-row">
					<td>{{item.dates}}</td>
					<td>{{item.days}}</td>
					<td>{{item.cost}}</td>
					<td>{{item.sum}}</td>
					<td v-on:click="rm(i)" class="remove-row">Убрать</td>
				</tr>
				<tr v-if="showHead" class="calc-result">
					<td>Итого:</td>
					<td>{{allDays}}</td>
					<td></td>
					<td>{{total}}</td>
				</tr>
			</table>
		</div>
	</div>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="js/datepicker.min.js"></script>
<script src="js/vue.js"></script>
<script>
    var localCRUD = {
        data: {
            key: 'CalCom'
        },
        methods: {
            save: function() {
                localStorage.setItem(
                    this.key,
                    JSON.stringify(this.items)
                );
                console.log('add');
            }
        }
    };
    
	var Periods = new Vue({
		el:'#periods',
		data: {
			items:[
				
			]
		},
		methods:{
			rm: function (index) {
				this.items.splice(index, 1);
                this.save();
			}
		},
		computed: {
			total: function() {
				var sum = 0;
				for (var i=0;i<this.items.length;i++) {
					sum += this.items[i]['sum'];
				}
				return sum;
			},
			allDays: function () {
				var sum = 0;
				for (var i=0;i<this.items.length;i++) {
					sum += this.items[i]['days'];
				}
				return sum;
			},
			showHead: function(){
				return !!this.items.length;
			}
		},
        mixins: [
            localCRUD
        ]
	});
    

    Periods.mixins = [localCRUD];

	$('.dp').datepicker({
		range: true,
		onSelect: function(res, datas, dp) {
			var price = +$('#price').val();

			if( 2 === datas.length && 0 !== price ) {
				var period = {};

				period['dates'] = res;
				period['days'] = (datas[1] - datas[0])/(1000*60*60*24) + 1;
				period['cost'] = price;
				period['sum'] = period['cost'] * period['days'];

				Periods.items.push(period);
                Periods.save();
                
                
			} else {
				console.log('Select 1 More Day');
			}
		}
	});

    var localData = localStorage.getItem(Periods.key);
    
    if ( null !== localData && '[]' !== localData ) {
        localData = JSON.parse(localData);
        
        Periods.items = localData;
        console.log(localData);
    }

	/// testing data
	$('#price').val(6400); // price
    
    var testData = {
		dates:"13.11.2017,30.11.2017",
		days:18,
		cost:2388,
		sum:42984
	};
	//Periods.items.push(testData); // table data

	/// ! testing

</script>
</body>
</html>