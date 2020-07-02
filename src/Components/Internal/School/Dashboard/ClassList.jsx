import React from 'react';
import Card from '@material-ui/core/Card';
import moment from 'moment';

function ClassList({ classList, classs }) {
    if (classs && (classs !== 'JHS 1' && classs !== 'JHS 2' && classs !== 'JHS 3' && classs !== 'SHS 1' && classs !== 'SHS 2' && classs !== 'SHS 3')) {
        classs = 'Class ' + classs;
    }
    
    return (
        <Card className="class-list-card" variant="outlined">
            {/* <h3>{classs} Students</h3> */}
            <h3>{classs ? classs+' Students' : 'You Haven\'t Been Assigned A Class Yet'}</h3>
            {   classs &&
                <table className="class-list">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Gender</th>
                            <th>Date Of Birth</th>
                        </tr>
                    </thead>
                    <tbody>
                        {
                            classList.map(student => {
                                return (
                                    <tr key={student.studentid}>
                                        <td>{student.name}</td>
                                        <td>{student.gender}</td>
                                        <td>
                                            {moment(student.dob).format('dddd[,] Do MMMM YYYY')}
                                        </td>
                                    </tr>
                                );
                            })
                        }
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Name</th>
                            <th>Gender</th>
                            <th>Date Of Birth</th>
                        </tr>
                    </tfoot>
                </table>
            }
        </Card>
    )
}

export default ClassList;
