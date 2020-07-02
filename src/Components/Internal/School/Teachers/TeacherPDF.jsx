import React from 'react';
import logo from '../../../../assets/riive.png';
import moment from "moment";
import { getBaseURL } from '../../../Extras/server';
import { Page, Text, View, Document, StyleSheet, Image } from "@react-pdf/renderer";

const styles = StyleSheet.create({
    page: {
        backgroundColor: "#ffffff"
    },
    container: {
        backgroundColor: "#f6f6f5",
        display: "flex",
        flexDirection: "row",
        padding: 5
    },
    image_div: {
        display: 'flex',
        justifyContent: 'center',
        alignItems: 'center',
    },
    image: {
        width: 200,
        height: 270,
        borderRadius: 120,
        border: '20px solid #aaa',
        marginBottom: 10,
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
    info_two_column: {
        display: 'flex',
        flexDirection: 'row',
        border: '1px solid #333',
        paddingBottom: 10,
        borderBottom: 'transparent',
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

function StudentPDF({ teacher, names }) {
    const image = getBaseURL()+teacher.image;
    return (
        <Document>
            <Page style={styles.page}>
                <View style={styles.container}>
                    <View>
                        <View>
                            <Image src={logo} />
                        </View>
                        <View style={styles.two_column}>
                            <Text style={styles.two_column_left}>Teacher Info</Text>
                            <Text style={styles.two_column_right}>{moment().format('dddd Do MMMM YYYY [at] hh:mm:ss')}</Text>
                        </View>
                        <View style={styles.image_div}>
                            <Image
                                style={styles.image}
                                src={image}
                                source={image} />
                        </View>
                        <View style={styles.info_two_column}>
                            <Text style={styles.info_two_column_left}>First Name:</Text>
                            <Text style={styles.info_two_column_right}>{names[0]}</Text>
                        </View>
                        <View style={styles.info_two_column}>
                            <Text style={styles.info_two_column_left}>Last Name:</Text>
                            <Text style={styles.info_two_column_right}>{names.length === 3 ? names[2] : names[1]}</Text>
                        </View>
                        <View style={styles.info_two_column}>
                            <Text style={styles.info_two_column_left}>Other Names:</Text>
                            <Text style={styles.info_two_column_right}>{names.length === 3 ? names[1] : null}</Text>
                        </View>
                        <View style={styles.info_two_column}>
                            <Text style={styles.info_two_column_left}>Phone Number:</Text>
                            <Text style={styles.info_two_column_right}>{teacher.contact}</Text>
                        </View>
                        <View style={styles.info_two_column}>
                            <Text style={styles.info_two_column_left}>Email Address:</Text>
                            <Text style={styles.info_two_column_right}>{teacher.email}</Text>
                        </View>
                    </View>
                </View>
            </Page>
        </Document>
    )
}

export default StudentPDF;
