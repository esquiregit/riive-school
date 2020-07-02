import React from 'react';
import moment from "moment";
import logo from '../../../../assets/riive.png';
import { Page, Text, View, Document, StyleSheet, Image } from "@react-pdf/renderer";

const styles = StyleSheet.create({
    page: {
        backgroundColor: "#ffffff"
    },
    container: {
        backgroundColor: "#f6f6f5",
        display: "flex",
        flexDirection: "row",
        padding: 5,
    },
    two_column: {
        display: 'flex',
        flexDirection: 'row',
        marginTop: 10,
        marginBottom: 10,
        borderTop: '1px solid #666',
        borderBottom: '1px solid #666',
        paddingTop: '5px',
        paddingBottom: '20px',
    },
    two_column_left: {
        textAlign: 'left',
        fontSize: 14,
    },
    two_column_right: {
        textAlign: 'right',
        fontSize: 14,
        marginRight: 95,
    },
    info_two_column_first: {
        display: 'flex',
        flexDirection: 'row',
        border: '1px solid #333',
        paddingBottom: 10,
        borderBottom: 'aransAssessment',
        marginTop: 20,
    },
    info_two_column: {
        display: 'flex',
        flexDirection: 'row',
        border: '1px solid #333',
        paddingBottom: 10,
        borderBottom: 'aransAssessment',
    },
    info_two_column_last: {
        display: 'flex',
        flexDirection: 'row',
        paddingBottom: 10,
        border: '1px solid #333',
    },
    info_two_column_left: {
        textAlign: 'right',
        fontSize: 15,
        padding: 10,
        flex: '1 1 50%',
        marginRight: 30,
        color: '#555',
    },
    info_two_column_right: {
        textAlign: 'left',
        fontSize: 15,
        marginRight: 95,
        padding: 10,
        flex: '1 2 50%',
    }
});

function AssessmentPDF({ assessment }) {
    return (
        <Document>
            <Page style={styles.page}>
                <View style={styles.container}>
                    <View>
                        <View>
                            <Image src={logo} />
                        </View>
                        <View style={styles.two_column}>
                            <Text style={styles.two_column_left}>Assessment Info</Text>
                            <Text style={styles.two_column_right}>{moment().format('dddd Do MMMM YYYY [at] hh:mm:ss')}</Text>
                        </View>
                        <View style={styles.info_two_column_first}>
                            <Text style={styles.info_two_column_left}>Student:</Text>
                            <Text style={styles.info_two_column_right}>{assessment.student}</Text>
                        </View>
                        <View style={styles.info_two_column}>
                            <Text style={styles.info_two_column_left}>Student Name:</Text>
                            <Text style={styles.info_two_column_right}>{assessment.student}</Text>
                        </View>
                        <View style={styles.info_two_column}>
                            <Text style={styles.info_two_column_left}>Class:</Text>
                            <Text style={styles.info_two_column_right}>{assessment.class}</Text>
                        </View>
                        <View style={styles.info_two_column}>
                            <Text style={styles.info_two_column_left}>Academic Year:</Text>
                            <Text style={styles.info_two_column_right}>{assessment.academic_year}</Text>
                        </View>
                        <View style={styles.info_two_column}>
                            <Text style={styles.info_two_column_left}>Term:</Text>
                            <Text style={styles.info_two_column_right}>{assessment.term}</Text>
                        </View>
                        <View style={styles.info_two_column_last}>
                            <Text style={styles.info_two_column_left}>Subject:</Text>
                            <Text style={styles.info_two_column_right}>{assessment.subject}</Text>
                        </View>
                        <View style={styles.info_two_column_last}>
                            <Text style={styles.info_two_column_left}>Class Tests:</Text>
                            <Text style={styles.info_two_column_right}>{assessment.class_tests}</Text>
                        </View>
                        <View style={styles.info_two_column_last}>
                            <Text style={styles.info_two_column_left}>Assignments:</Text>
                            <Text style={styles.info_two_column_right}>{assessment.assignments}</Text>
                        </View>
                        <View style={styles.info_two_column_last}>
                            <Text style={styles.info_two_column_left}>Interim Assessment:</Text>
                            <Text style={styles.info_two_column_right}>{assessment.interim_assessment}</Text>
                        </View>
                        <View style={styles.info_two_column_last}>
                            <Text style={styles.info_two_column_left}>Attendance:</Text>
                            <Text style={styles.info_two_column_right}>{assessment.attendance_mark}</Text>
                        </View>
                        <View style={styles.info_two_column_last}>
                            <Text style={styles.info_two_column_left}>Exams:</Text>
                            <Text style={styles.info_two_column_right}>{assessment.exams_score}</Text>
                        </View>
                        <View style={styles.info_two_column_last}>
                            <Text style={styles.info_two_column_left}>Total Score:</Text>
                            <Text style={styles.info_two_column_right}>{assessment.total_score}</Text>
                        </View>
                        <View style={styles.info_two_column_last}>
                            <Text style={styles.info_two_column_left}>Grade:</Text>
                            <Text style={styles.info_two_column_right}>{assessment.grade}</Text>
                        </View>
                        <View style={styles.info_two_column_last}>
                            <Text style={styles.info_two_column_left}>Remarks:</Text>
                            <Text style={styles.info_two_column_right}>{assessment.remarks}</Text>
                        </View>
                        <View style={styles.info_two_column_last}>
                            <Text style={styles.info_two_column_left}>Date:</Text>
                            <Text style={styles.info_two_column_right}>{assessment.date_entered}</Text>
                        </View>
                    </View>
                </View>
            </Page>
        </Document>
    )
}

export default AssessmentPDF;
