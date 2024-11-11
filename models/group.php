<?php

require_once __DIR__ . '/../functions/mysql_connection.php';
require_once __DIR__ . '/base_model.php';
require_once __DIR__ . '/school_year.php';
require_once __DIR__ . '/education_level.php';

final class Group implements BaseModel {
    private static $select = 
        'SELECT  
         FROM groupos 
         WHERE numero = ?';

    private static $select_all = 
        'SELECT codigo, descripcion, edad_minima, edad_maxima, cantidad_grados 
         FROM niveles_educativos';

    // attributes
    private $number;
    private $grade;
    private $letter;
    private $school_year;
    private $education_level;

    // getters
    public function get_number() : string {
        return $this->number;
    }

    public function get_grade() : int {
        return $this->grade;
    }

    public function get_letter() : string {
        return $this->letter;
    }

    public function get_school_year() : SchoolYear {
        return $this->school_year;
    }

    public function get_education_level() : EducationLevel {
        return $this->education_level;
    }

    public function to_json_string() : string {
        $data = [
            'number' => $this->number,
            'grade'=> $this->grade,
            'letter' => $this->letter,
            'school_year'=> $this->school_year,
            'education_level'=> $this->education_level
        ];

        return json_encode($data);
    }

    // constructor
    public function __construct(
        string $number, 
        int $grade,
        string $letter,
        SchoolYear $school_year,
        EducationLevel $education_level
    ) {
        $this->number = $number;
        $this->grade = $grade;
        $this->letter = $letter;
        $this->school_year = $school_year;
        $this->education_level = $education_level;
    }

    public static function get(string $code): EducationLevel|null {
        // declare variable to store the retrieved object
        $level = null;
        // open a new connection
        $connection = MySqlConnection::open_connection();
        // prepare statement
        $command = $connection->prepare(self::$select);
        // bind param
        $command->bind_param('s', $code);
        // execute statement
        $command->execute();
        // bind results
        $command->bind_result(
            $code, 
            $description, 
            $minimum_age, 
            $maximum_age,
            $grade_count
        );

        // read result
        if ($command->fetch()) {
            $level = new EducationLevel(
                $code, $description, $minimum_age, $maximum_age, $grade_count
            );
        }

        // deallocate resources
        $command->close();
        // close connection
        $connection->close();

        return $level;
    }

    public static function get_all() : array {
        // create empty array
        $list = [];
        // open a new connection
        $connection = MySqlConnection::open_connection();
        // prepare statement
        $command = $connection->prepare(self::$select_all);
        // execute statement
        $command->execute();
        // bind results
        $command->bind_result(
            $code, 
            $name, 
            $minimum_age, 
            $maximum_age,
            $grade_count
        );

        // read result
        while ($command->fetch()) {
            array_push(
                $list, 
                new EducationLevel(
                    $code, 
                    $name, 
                    $minimum_age, 
                    $maximum_age,
                    $grade_count
                )
            );
        }

        // deallocate resources
        $command->close();
        // close connection
        $connection->close();

        return $list;
    }
}