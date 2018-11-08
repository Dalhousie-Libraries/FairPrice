<template>
        <div>
            <div class='row'>
                <div class='col-md-3'>
                    <label for='faculty'>Please select your Faculty: </label><br/>
                    <select id='faculty' v-model='selectedfaculty'>
                        <option v-for='(faculty, index) in faculties' :value='faculty.id'>{{faculty.faculty_name}}</option>
                    </select><br/>
                </div>
                <div class='col-md-6'>
                    <div v-if='type != 1' id='department_controls'>
                        <label for='department'>Please select your Department: </label><br/>
                        <select id='department' v-model='selecteddepartment'>
                            <option v-if="this.selectedfaculty=='none'" value=''>Please select a faculty</option>
                            <option v-for='(department, index) in filtereddepartments' :value='department.id'>{{department.department_name}}</option>
                        </select><br/>
                    </div>
                </div>

                
            </div>
            <div class='row'>
                <div class='col-md-6 col-md-offset-6'>
                    <button id='save' class='btn btn-primary' v-on:click='saveData()'>Continue</button>
                </div>
            </div>
        </div>    
</template>

<script>
 export default {
        props: ['prop_faculties', 'prop_departments', 'type', 'posturl'],
        data: function() {
            return {
                selecteddepartment: "",
                selectedfaculty: 1,
                faculties: JSON.parse(this.prop_faculties),
                departments: JSON.parse(this.prop_departments),
            }
        },
        computed: {
            filtereddepartments: function() {
                var self = this;
                
                if(this.selectedfaculty == 'none') {
                    this.selecteddepartment = '';
                    $("#department_controls").addClass('hidden');
                    return [];
                } else {
                    $("#department_controls").removeClass('hidden');
                    return this.departments.filter(function(p){
                        return p.faculty_id == self.selectedfaculty;
                    });
                }
            },
        },
        methods: {
            saveData: function() {
                var data = {};
                Vue.http.interceptors.push(function (request, next) {
                    request.headers['X-CSRF-TOKEN'] = Laravel.csrfToken;
                    next();
                });
                data.faculty = this.selectedfaculty;
                data.department = this.selecteddepartment;

                data['X-CSRF-TOKEN'] = $('meta[name="csrf-token"]').attr('content');
                axios.post(this.posturl, data).then(function (response) {
                    // Success
                    if(response.data.result == true) {
                        $('successmsg').val(response.data.message);
                        $('error').addClass('hidden');
                        $('success').removeClass('hidden');
                        window.location.href='https://' + document.location.hostname + '/home';
                    } else {
                        $('errormsg').val(response.data.message);
                        $('success').addClass('hidden');
                        $('error').removeClass('hidden');
                    }
                },function (response) {
                    // Error
                    console.log(response.data)
                });
            },
        },
        mounted() {
        }
    }
</script>

