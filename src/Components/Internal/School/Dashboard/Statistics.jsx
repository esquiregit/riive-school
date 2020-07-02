import React from 'react';
import Card from '@material-ui/core/Card';
import Grid from '@material-ui/core/Grid';
import styles from '../../../Extras/styles';
import Typography from '@material-ui/core/Typography';
import CardContent from '@material-ui/core/CardContent';
import LocalLibraryOutlinedIcon from '@material-ui/icons/LocalLibraryOutlined';
import VerifiedUserOutlinedIcon from '@material-ui/icons/VerifiedUserOutlined';
import RecordVoiceOverOutlinedIcon from '@material-ui/icons/RecordVoiceOverOutlined';

function Statistics({ total_students = 0, total_teachers = 0, total_security = 0 }) {
    const classes = styles();
    return (
        <Grid container spacing={3} className="mt-20">
            <Grid item xs={12} md={4}>
                <Card className={classes.root} variant="outlined">
                    <CardContent>
                        <div>
                            <LocalLibraryOutlinedIcon
                                className="fs-55" />
                        </div>
                        <div>
                            <Typography
                                variant="h5"
                                component="h2"
                                className="text-right">
                                {total_students}
                            </Typography>
                            <Typography
                                className="text-right"
                                color="textSecondary">
                                Students
                            </Typography>
                        </div>
                    </CardContent>
                </Card>
            </Grid>
            <Grid item xs={12} md={4}>
                <Card className={classes.root} variant="outlined">
                    <CardContent>
                        <div>
                            <RecordVoiceOverOutlinedIcon
                                className="fs-55" />
                        </div>
                        <div>
                            <Typography
                                variant="h5"
                                component="h2"
                                className="text-right">
                                {total_teachers}
                            </Typography>
                            <Typography
                                className="text-right"
                                color="textSecondary">
                                Teachers
                            </Typography>
                        </div>
                    </CardContent>
                </Card>
            </Grid>
            <Grid item xs={12} md={4}>
                <Card className={classes.root} variant="outlined">
                    <CardContent>
                        <div>
                            <VerifiedUserOutlinedIcon
                                className="fs-55" />
                        </div>
                        <div>
                            <Typography
                                variant="h5"
                                component="h2"
                                className="text-right">
                                {total_security}
                            </Typography>
                            <Typography
                                className="text-right"
                                color="textSecondary">
                                Security
                            </Typography>
                        </div>
                    </CardContent>
                </Card>
            </Grid>
        </Grid>
    )
}

export default Statistics;
