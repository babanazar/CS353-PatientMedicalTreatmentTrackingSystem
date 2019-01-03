import java.sql.DriverManager;
import java.sql.Connection;
import java.sql.Statement;
import java.sql.SQLException;
import java.sql.PreparedStatement;
public class Main {
    public static void main(String[] args) throws SQLException {
        String url = "jdbc:mysql://dijkstra.ug.bcc.bilkent.edu.tr/islomiddin";
        String username = "islomiddin";
        String password = "1cf78fqf";
        Connection connection;
        try {
            connection = DriverManager.getConnection(url, username, password);
        } catch (SQLException e) {
            throw new SQLException("Wrong URL or username or password. Please, check those and try again.");
        }
        Statement statement = connection.createStatement();

        //variables needed for inserting tuples into tables
        String query;
        String[][] tuples;

        statement.executeUpdate("DROP TABLE IF EXISTS contains");
        statement.executeUpdate("DROP TABLE IF EXISTS consists");
        statement.executeUpdate("DROP TABLE IF EXISTS transaction");
        statement.executeUpdate("DROP TABLE IF EXISTS has");
        statement.executeUpdate("DROP TABLE IF EXISTS does");
        statement.executeUpdate("DROP TABLE IF EXISTS asks_for");
        statement.executeUpdate("DROP TABLE IF EXISTS prescribes");
        statement.executeUpdate("DROP TABLE IF EXISTS results");
        statement.executeUpdate("DROP TABLE IF EXISTS diagnose");
        statement.executeUpdate("DROP TABLE IF EXISTS appointment");
        statement.executeUpdate("DROP TABLE IF EXISTS pharmacist");
        statement.executeUpdate("DROP TABLE IF EXISTS patient");
        statement.executeUpdate("DROP TABLE IF EXISTS doctor");
        statement.executeUpdate("DROP TABLE IF EXISTS user");
        statement.executeUpdate("DROP TABLE IF EXISTS hospital");
        statement.executeUpdate("DROP TABLE IF EXISTS address");
        statement.executeUpdate("DROP TABLE IF EXISTS test");
        statement.executeUpdate("DROP TABLE IF EXISTS symptom");
        statement.executeUpdate("DROP TABLE IF EXISTS disease");
        statement.executeUpdate("DROP TABLE IF EXISTS prescription");
        statement.executeUpdate("DROP TABLE IF EXISTS drug");
        statement.executeUpdate("DROP TABLE IF EXISTS component");

        //Creating tables and inserting values
        statement.executeUpdate("CREATE TABLE address(add_id int NOT NULL PRIMARY KEY AUTO_INCREMENT, " +
                                "country varchar(20) NOT NULL," +
                                "city varchar(20) NOT NULL," +
                                "street varchar(25) NOT NULL," +
                                "apartment varchar(20)," +
                                "apartment_num int)" +
                                "ENGINE=INNODB");

        query = "INSERT INTO address (country, city, street, apartment, apartment_num) VALUES(?,?,?,?,?)";
        tuples = new String[][]{
                {"Turkey", "Ankara", "Tunus", "A1", "14"},
                {"Turkey", "Istanbul", "1996", "A2", "47"}};
        /*for (int i = 0; i < tuples.length; ++i) {
            preparedStatement = connection.prepareStatement(query);
            for (int j = 0; j < tuples[i].length; ++j) {
                preparedStatement.setString(j + 1, tuples[i][j]);
            }
            preparedStatement.executeUpdate();
        }*/
        insert(connection, query, tuples);

        statement.executeUpdate("CREATE TABLE user(username varchar(25) NOT NULL PRIMARY KEY," +
                                "image LONGBLOB," +
                                "phone varchar(20) NOT NULL," +
                                "name varchar(20) NOT NULL," +
                                "password varchar(20) NOT NULL," +
                                "birthday date NOT NULL," +
                                "gender varchar(20)," +
                                "add_id int NOT NULL," +
                                "FOREIGN KEY (add_id) REFERENCES address(add_id)," +
                                "UNIQUE (username))" +
                                "ENGINE=INNODB");

        query = "INSERT INTO user (username, image, phone, name, password, birthday, gender, add_id) VALUES(?,?,?,?,?,?,?,?)";
        tuples = new String[][]{
                {"user1", "LOAD_FILE('D:/Users/TEMP.PCLABS/Desktop/user1.png')", "123456789", "Ali", "ali1", "1996-05-21", "male", "1"},
                {"user2", "LOAD_FILE('D:/Users/TEMP.PCLABS/Desktop/user2.png')", "987654321", "Ayse", "ayse1", "1996-05-22", "female", "2"},
                {"user3", "LOAD_FILE('D:/Users/TEMP.PCLABS/Desktop/user1.png')", "546847984", "John", "john1", "1989-12-31", "male", "1"},
                {"user4", "LOAD_FILE('D:/Users/TEMP.PCLABS/Desktop/user2.png')", "164184455", "Amy", "amy1", "1990-1-18", "female", "2"}};
        insert(connection, query, tuples);

        statement.executeUpdate("CREATE TABLE pharmacist(username varchar(25) NOT NULL," +
                                "delivery_cost float(2) NOT NULL DEFAULT 0," +
                                "discount int DEFAULT 0," +
                                "FOREIGN KEY (username) REFERENCES user(username)," +
                                "UNIQUE (username))" +
                                "ENGINE=INNODB");

        query = "INSERT INTO pharmacist (username, delivery_cost, discount) VALUES(?,?,?)";
        tuples = new String[][]{{"user1", "12.34", "10"}, {"user2", "56.78", "20"}};
        insert(connection, query, tuples);

        statement.executeUpdate("CREATE TABLE patient(username varchar(25) NOT NULL," +
                                "FOREIGN KEY(username) REFERENCES user(username))" +
                                "ENGINE=INNODB");

        query = "INSERT INTO patient (username) VALUES (?)";
        tuples = new String[][]{{"user1"}, {"user2"}};
        insert(connection, query, tuples);

        statement.executeUpdate("CREATE TABLE hospital(hos_id int NOT NULL PRIMARY KEY AUTO_INCREMENT," +
                                "name varchar(20) NOT NULL," +
                                "phone varchar(20) NOT NULL," +
                                "image LONGBLOB," +
                                "add_id int NOT NULL," +
                                "FOREIGN KEY(add_id) REFERENCES address(add_id))");

        query = "INSERT INTO hospital(name, phone, image, add_id) VALUES (?,?,?,?)";
        tuples = new String[][]{
                {"abc clinic", "012345", "LOAD_FILE('D:/Users/TEMP.PCLABS/Desktop/hosp1.png')", "1"},
                {"def clinic", "678910", "LOAD_FILE('D:/Users/TEMP.PCLABS/Desktop/hosp2.png')", "2"}};
        insert(connection, query, tuples);

        statement.executeUpdate("CREATE TABLE doctor(username varchar(25) NOT NULL," +
                                "specialization varchar(30) NOT NULL," +
                                "experience int DEFAULT 0," +
                                "education varchar(25) NOT NULL," +
                                "work_time_begin time NOT NULL," +
                                "work_time_end time NOT NULL," +
                                "hos_id int," +
                                "FOREIGN KEY(username) REFERENCES user(username)," +
                                "FOREIGN KEY(hos_id) REFERENCES hospital(hos_id))" +
                                "ENGINE=INNODB");

        query = "INSERT INTO doctor (username, specialization, experience, education, work_time_begin, work_time_end, hos_id) VALUES (?,?,?,?,?,?,?)";
        tuples = new String[][]{
                {"user3", "Addiction psychiatrist", "5", "Bilkent University", "10:00", "18:00", "1"},
                {"user4", "Cardiologist", "7", "METU University", "9:00", "15:00", "2"}};
        insert(connection, query, tuples);

        statement.executeUpdate("CREATE TABLE appointment(app_id int NOT NULL PRIMARY KEY AUTO_INCREMENT," +
                                "status varchar(25) NOT NULL," +
                                "date date NOT NULL," +
                                "time time NOT NULL," +
                                "patient_username varchar(25) NOT NULL," +
                                "doctor_username varchar(25) NOT NULL," +
                                "FOREIGN KEY(patient_username) REFERENCES patient(username)," +
                                "FOREIGN KEY(doctor_username) REFERENCES doctor(username))" +
                                "ENGINE=INNODB");

        query = "INSERT INTO appointment (status, date, time, patient_username, doctor_username) VALUES (?,?,?,?,?)";
        tuples = new String[][]{
                {"pending approval", "2019-02-12", "13:00", "user1", "user4"},
                {"approved", "2019-04-12", "11:00", "user2", "user3"}};
        insert(connection, query, tuples);

        statement.executeUpdate("CREATE TABLE test(test_id int NOT NULL PRIMARY KEY AUTO_INCREMENT," +
                                "name varchar(40) NOT NULL," +
                                "result varchar(40) NOT NULL)" +
                                "ENGINE=INNODB");

        query = "INSERT INTO test(name, result) VALUES(?,?)";
        tuples = new String[][]{{"MRI", "normal"}, {"Echocardiography", "normal"}};
        insert(connection, query, tuples);

        statement.executeUpdate("CREATE TABLE symptom(symp_name varchar(30) NOT NULL PRIMARY KEY," +
                                "type varchar(25) NOT NULL," +
                                "description varchar(40))" +
                                "ENGINE=INNODB");

        query = "INSERT INTO symptom (symp_name, type, description) VALUES (?,?,?)";
        tuples = new String[][]{
                {"anorexia", "general", "weight loss"},
                {"chills", "general", "cold"},
                {"muscle weakness", "general", " "},
                {"sweats", "general", " "},
                {"headache", "general", "pain in the head"},
                {"chest pain", "cardiovascular", "pain in the chest"},
                {"tachycardia ", "cardiovascular", "is a heart rate that exceeds the normal resting rate"},
                {"hair", "integumentary", "any problem with hair"},
                {"skin", "integumentary", "any problem with skin"},
                {"apathy", "psychiatric", "lack of interest, enthusiasm, or concern"},
                {"phobia", "psychiatric", "fear of something"},
                {"euphoria", "psychiatric", " "},
                {"depression", "psychiatric", " "},
                {"confusion", "neurological", "uncertainty about what is happening, intended, or required"},
                {"burnout", "neurological", "if you are doing cs353 project"},
                {"headache", "head related", "pain in the head"},
                {"fever", "body temperature", "high body temperature"}};
        insert(connection, query, tuples);

        statement.executeUpdate("CREATE TABLE disease(dis_id int NOT NULL PRIMARY KEY AUTO_INCREMENT," +
                                "name varchar(20) NOT NULL," +
                                "degree varchar(20))" +
                                "ENGINE=INNODB");

        query = "INSERT INTO disease (name, degree) VALUES (?,?)";
        tuples = new String[][]{{"Asthma", "1"}, {"Autism", "2"}};
        insert(connection, query, tuples);

        statement.executeUpdate("CREATE TABLE prescription(prescription_id int NOT NULL PRIMARY KEY AUTO_INCREMENT," +
                                "date date NOT NULL)" +
                                "ENGINE=INNODB");

        query = "INSERT INTO prescription (date) VALUES (?)";
        tuples = new String[][]{{"2019-02-12"}, {"2019-04-12"}};
        insert(connection, query, tuples);

        statement.executeUpdate("CREATE TABLE drug(drug_id int NOT NULL PRIMARY KEY AUTO_INCREMENT," +
                                "name varchar(25) NOT NULL," +
                                "producer varchar(25) NOT NULL," +
                                "price float(2) NOT NULL DEFAULT 0," +
                                "image LONGBLOB)" +
                                "ENGINE=INNODB");

        query = "INSERT INTO drug (name, producer, price, image) VALUES (?,?,?,?)";
        tuples = new String[][]{
                {"drug1", "company1", "25.55", "LOAD_FILE('D:/Users/TEMP.PCLABS/Desktop/drug1.jpg')"},
                {"drug2", "company2", "35.55", "LOAD_FILE('D:/Users/TEMP.PCLABS/Desktop/drug2.png')"}};
        insert(connection, query, tuples);

        statement.executeUpdate("CREATE TABLE transaction(trans_id int NOT NULL PRIMARY KEY AUTO_INCREMENT," +
                                "total_price float(0) NOT NULL DEFAULT 0," +
                                "date date NOT NULL," +
                                "time time NOT NULL," +
                                "status varchar(25)," +
                                "patient_username varchar(25) NOT NULL," +
                                "FOREIGN KEY(patient_username) REFERENCES patient(username))" +
                                "ENGINE=INNODB");

        query = "INSERT INTO transaction (total_price, date, time, status, patient_username) VALUES (?,?,?,?,?)";
        tuples = new String[][]{
                {"20", "2019-02-12", "18:00", "purchased", "user1"},
                {"30", "2019-04-12", "15:00", "waiting order", "user2"}};
        insert(connection, query, tuples);

        statement.executeUpdate("CREATE TABLE does(test_id int NOT NULL," +
                                "app_id int NOT NULL," +
                                "doctor_username varchar(25) NOT NULL," +
                                "PRIMARY KEY(test_id, app_id, doctor_username)," +
                                "FOREIGN KEY (test_id) REFERENCES test(test_id)," +
                                "FOREIGN KEY (app_id) REFERENCES appointment(app_id)," +
                                "FOREIGN KEY (doctor_username) REFERENCES doctor(username))" +
                                "ENGINE=INNODB");

        query = "INSERT INTO does (test_id, app_id, doctor_username) VALUES (?,?,?)";
        tuples = new String[][]{{"1", "2", "user3"}, {"2", "1", "user4"}};
        insert(connection, query, tuples);

        statement.executeUpdate("CREATE TABLE has(pharmacist_username varchar(25) NOT NULL," +
                                "drug_id int NOT NULL," +
                                "stock int NOT NULL," +
                                "PRIMARY KEY(pharmacist_username, drug_id)," +
                                "FOREIGN KEY (pharmacist_username) REFERENCES pharmacist(username)," +
                                "FOREIGN KEY (drug_id) REFERENCES drug(drug_id))" +
                                "ENGINE=INNODB");

        query = "INSERT INTO has (pharmacist_username, drug_id, stock) VALUES (?,?,?)";
        tuples = new String[][]{{"user1", "1", "400"}, {"user2", "2", "200"}};
        insert(connection, query, tuples);

        statement.executeUpdate("CREATE TABLE asks_for(symp_name varchar(25) NOT NULL," +
                                "app_id int NOT NULL," +
                                "PRIMARY KEY(symp_name, app_id)," +
                                "FOREIGN KEY (symp_name) REFERENCES symptom(symp_name)," +
                                "FOREIGN KEY (app_id) REFERENCES appointment(app_id))" +
                                "ENGINE=INNODB");

        query = "INSERT INTO asks_for (symp_name, app_id) VALUES (?,?)";
        tuples = new String[][]{{"headache", "1"}, {"fever", "2"}};
        insert(connection, query, tuples);

        statement.executeUpdate("CREATE TABLE prescribes(pres_id int NOT NULL," +
                                "drug_id int NOT NULL," +
                                "description varchar(40) NOT NULL," +
                                "amount int NOT NULL," +
                                "PRIMARY KEY (pres_id, drug_id)," +
                                "FOREIGN KEY (pres_id) REFERENCES prescription (prescription_id)," +
                                "FOREIGN KEY (drug_id) REFERENCES drug (drug_id))" +
                                "ENGINE=INNODB");

        query = "INSERT INTO prescribes (pres_id, drug_id, description, amount) VALUES (?,?,?,?)";
        tuples = new String[][]{{"1", "1", "drink 2 pills before sleep", "3"}, {"2", "1", "drink one pill per day", "5"}};
        insert(connection, query, tuples);

        statement.executeUpdate("CREATE TABLE contains(trans_id int PRIMARY KEY NOT NULL," +
                                "pharmacist_username varchar(25) NOT NULL," +
                                "pres_id int NOT NULL," +
                                "FOREIGN KEY (trans_id) REFERENCES transaction(trans_id)," +
                                "FOREIGN KEY (pharmacist_username) REFERENCES pharmacist(username)," +
                                "FOREIGN KEY (pres_id) REFERENCES prescription(prescription_id))" +
                                "ENGINE=INNODB");
        query = "INSERT INTO contains(trans_id, pharmacist_username, pres_id) VALUES (?,?,?)";
        tuples = new String[][]{{"1", "user1", "2"}, {"2", "user2", "1"}};
        insert(connection, query, tuples);

        statement.executeUpdate("CREATE TABLE diagnose(app_id int NOT NULL," +
                                "dis_id int NOT NULL," +
                                "PRIMARY KEY(app_id, dis_id)," +
                                "FOREIGN KEY (app_id) REFERENCES appointment(app_id)," +
                                "FOREIGN KEY (dis_id) REFERENCES disease(dis_id))" +
                                "ENGINE=INNODB");
        query = "INSERT INTO diagnose (app_id, dis_id) VALUES (?,?)";
        tuples = new String[][]{{"1", "2"}, {"2", "1"}};
        insert(connection, query, tuples);

        statement.executeUpdate("CREATE TABLE results(app_id int NOT NULL," +
                                "pres_id int NOT NULL," +
                                "PRIMARY KEY(app_id, pres_id)," +
                                "FOREIGN KEY (app_id) REFERENCES appointment(app_id)," +
                                "FOREIGN KEY (pres_id) REFERENCES prescription(prescription_id)," +
                                "UNIQUE(pres_id))" +
                                "ENGINE=INNODB");

        query = "INSERT INTO results (app_id, pres_id) VALUES (?,?)";
        tuples = new String[][]{{"2", "2"}, {"2", "1"}};
        insert(connection, query, tuples);

        statement.executeUpdate("CREATE TABLE component(name varchar(30) PRIMARY KEY NOT NULL," +
                                "description varchar(40)," +
                                "UNIQUE(name))" +
                                "ENGINE=INNODB");

        query = "INSERT INTO component (name, description) VALUES (?,?)";
        tuples = new String[][]{{"H2O", "water"}, {"B12", "vitamin"}};
        insert(connection, query, tuples);

        statement.executeUpdate("CREATE TABLE consists(drug_id int NOT NULL," +
                                "comp_name varchar(30) NOT NULL," +
                                "PRIMARY KEY(drug_id, comp_name)," +
                                "FOREIGN KEY (drug_id) REFERENCES drug(drug_id)," +
                                "FOREIGN KEY (comp_name) REFERENCES component(name))" +
                                "ENGINE=INNODB");

        query = "INSERT INTO consists (drug_id, comp_name) VALUES (?,?)";
        tuples = new String[][]{{"1", "H2O"}, {"2", "B12"}};
        insert(connection, query, tuples);
    }
    static void insert(Connection connection, String query, String[][] tuples) throws SQLException {
        PreparedStatement preparedStatement;
        for (int i = 0; i < tuples.length; ++i) {
            preparedStatement = connection.prepareStatement(query);
            for (int j = 0; j < tuples[i].length; ++j) {
                preparedStatement.setString(j + 1, tuples[i][j]);
            }
            preparedStatement.executeUpdate();
        }
    }
}
