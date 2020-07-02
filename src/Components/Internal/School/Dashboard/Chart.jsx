import React from 'react';
import Card from '@material-ui/core/Card';
import { Bar } from 'react-chartjs-2';

function Chart({ stats }) {
    const data = {
        labels: [
            'Creche',
            'Nursery 1',
            'Nursery 2',
            'Kindergarten',
            'Class 1',
            'Class 2',
            'Class 3',
            'Class 4',
            'Class 5',
            'Class 6',
            'JSH 1',
            'JSH 2',
            'JSH 3'
        ],
        datasets: [
            {
                label: 'Male Students',
                data : [
                    stats.creche_male,
                    stats.nursery_1_male,
                    stats.nursery_2_male,
                    stats.kindergarten_male,
                    stats.one_male,
                    stats.two_male,
                    stats.three_male,
                    stats.four_male,
                    stats.five_male,
                    stats.six_male,
                    stats.jhs_1_male,
                    stats.jhs_2_male,
                    stats.jhs_3_male
                ],
                borderColor: [
                    '#228CDB',
                    '#228CDB',
                    '#228CDB',
                    '#228CDB',
                    '#228CDB',
                    '#228CDB',
                    '#228CDB',
                    '#228CDB',
                    '#228CDB',
                    '#228CDB',
                    '#228CDB',
                    '#228CDB',
                    '#228CDB',
                ],
                backgroundColor: [
                    '#228CDB',
                    '#228CDB',
                    '#228CDB',
                    '#228CDB',
                    '#228CDB',
                    '#228CDB',
                    '#228CDB',
                    '#228CDB',
                    '#228CDB',
                    '#228CDB',
                    '#228CDB',
                    '#228CDB',
                    '#228CDB',
                ]
            },
            {
                label: 'Female Students',
                data : [
                    stats.creche_female,
                    stats.nursery_1_female,
                    stats.nursery_2_female,
                    stats.kindergarten_female,
                    stats.one_female,
                    stats.two_female,
                    stats.three_female,
                    stats.four_female,
                    stats.five_female,
                    stats.six_female,
                    stats.jhs_1_female,
                    stats.jhs_2_female,
                    stats.jhs_3_female
                ],
                borderColor: [
                    '#F48498',
                    '#F48498',
                    '#F48498',
                    '#F48498',
                    '#F48498',
                    '#F48498',
                    '#F48498',
                    '#F48498',
                    '#F48498',
                    '#F48498',
                    '#F48498',
                    '#F48498',
                    '#F48498',
                ],
                backgroundColor: [
                    '#F48498',
                    '#F48498',
                    '#F48498',
                    '#F48498',
                    '#F48498',
                    '#F48498',
                    '#F48498',
                    '#F48498',
                    '#F48498',
                    '#F48498',
                    '#F48498',
                    '#F48498',
                    '#F48498',
                ]
            },
            {
                label: 'Total Students',
                data : [
                    stats.creche_male + stats.creche_female,
                    stats.nursery_1_male + stats.nursery_1_female,
                    stats.nursery_2_male + stats.nursery_2_female,
                    stats.kindergarten_male + stats.kindergarten_female,
                    stats.one_male + stats.one_female,
                    stats.two_male + stats.two_female,
                    stats.three_male + stats.three_female,
                    stats.four_male + stats.four_female,
                    stats.five_male + stats.five_female,
                    stats.six_male + stats.six_female,
                    stats.jhs_1_male + stats.jhs_1_female,
                    stats.jhs_2_male + stats.jhs_2_female,
                    stats.jhs_3_male + stats.jhs_3_female,
                ],
                borderColor: [
                    '#393A10',
                    '#393A10',
                    '#393A10',
                    '#393A10',
                    '#393A10',
                    '#393A10',
                    '#393A10',
                    '#393A10',
                    '#393A10',
                    '#393A10',
                    '#393A10',
                    '#393A10',
                    '#393A10',
                ],
                backgroundColor: [
                    '#393A10',
                    '#393A10',
                    '#393A10',
                    '#393A10',
                    '#393A10',
                    '#393A10',
                    '#393A10',
                    '#393A10',
                    '#393A10',
                    '#393A10',
                    '#393A10',
                    '#393A10',
                    '#393A10',
                ]
            }
        ]
    }
    const options = {
        title: {
            display: true,
            text: 'Class/Gender Distribution'
        },
        // scales: {
        //     yAxes: [
        //         {
        //             ticks: {
        //                 min: 0,
        //                 max: 50,
        //                 stepSize: 10
        //             }
        //         }
        //     ]
        // }
    }

    return (
        <Card variant="outlined">
            <Bar data={data} options={options} />
        </Card>
    )
}

export default Chart;
