<template>
    <div >
        <div class="row ">
            <div class="col-md-offset-8 col-md-4">
                <input type="text" class="form-control" v-model="filter" placeholder="search" @keyup="search()">
            </div>
        </div>
        <table class="table loader-form" v-cloak >
            <thead>
                <tr class="tr">
                    <th></th>
                    <th>Name</th>
                    <th>School</th>
                    <th>Division</th>
                </tr>
            </thead>
            <tbody v-if="resultData.data !=''" >
                <tr class="tr" v-for="(value,index) in resultData.data" >
                    <td>{{ index + 1 }}</td>
                    <td>{{ value.last_name }}, {{ value.first_name }}</td>
                    <td><div v-if="value.school">{{ value.school.name }}</div></td>
                    <td><div v-if="value.school && value.school.division">{{ value.school.division.name }}</div></td>
                    <!--<td>-->
                        <!--<a href="" class="btn btn-success btn-sm" @click.prevent="openUpdateModal(value.id)">-->
                            <!--<span class="glyphicon glyphicon-pencil"></span> Update-->
                        <!--</a>-->
                        <!--<a v-on:click="deleteUser(value.id, index)"  class="btn btn-danger btn-sm">-->
                            <!--<span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Delete-->
                        <!--</a>-->
                    <!--</td>-->
                </tr>
            </tbody>
            <tbody v-else>
                <tr>
                    <td colspan="2">No data have found !</td>
                </tr>
            </tbody>
        </table>
        <div class="row">
            <div class="text-center col-md-12" >
                <pagination :resultData="resultData" @clicked="index" :mid-size="9"></pagination>
            </div>
        </div>
    </div>
</template>

<script>
    import { EventBus } from '../vue-assets';
    import Pagination from  './Pagination.vue';

    export default {
        components: {
            Pagination
        },
        data: function(){
            return {
                resultData: {},
                filter: ''
            };
        },
        methods: {
            index(pageNo,filter){
                if(pageNo){
                    pageNo = pageNo;
                }else{
                    pageNo = this.resultData.current_page;
                }
                if(filter){
                    filter = filter;
                }else{
                    filter = '';
                }

                axios.get(base_url+"examinees?page="+pageNo+"&filter="+filter).then((res) => {
                    this.resultData = res.data;
                });

            },

            deleteUser(id, index) {
                var vueThis = this;
                if (confirm("Do you really want to delete it?")) {
                    axios.delete(base_url+'examinees/' + id).then((response) => {
                        if(response.data.status == 'success'){
                            EventBus.$emit('user-created', response.data);
                            this.showMassage(response.data);
                        }else{
                            this.showMassage(response.data);
                        }
                    }).catch(error => {
                        this.showMassage(response.data);
                    });
                }
            },

            showMassage(data) {
                if (data.status == 'success') {
                    toastr.success(data.message, 'Success Alert', {timeOut: 5000});
                } else {
                    toastr.error(data.message, 'Error Alert', {timeOut: 5000});
                }
            },

            openUpdateModal(id){
                EventBus.$emit('update-buton-clicked', id);
            },

            search(){
                var vm = this;
                vm.index(1,vm.filter);
            },

            range(start, count) {
                return Array.apply(0, Array(count))
                    .map(function (element, index){
                        return index + start;
                    });
            },
        },

        created() {
           var _this = this;
           _this.index(1);
            EventBus.$on('examinee-created', function () {
                _this.index(1);
            });
        }

    };
</script>
