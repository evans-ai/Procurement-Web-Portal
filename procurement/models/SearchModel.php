<?php
namespace procurement\models;

use Yii;
use yii\base\Model;


class SearchModel extends Model 
{

public function rules()
    {
        return [
            // username and password are both required
            //[['No'], 'required'],

        ];
    }

    public function SearchModel($params) {
        $query = DocumentsSearch::find()->where(['<>', 'Tender_No', 0])->andwhere(['<>', 'Supplier_Name', 0]); //->where(['fee_id' <> 0 AND 'inspector_id' <> 0])

        $query->joinWith(['Tender_No','Supplier_Name']);


        $dataProvider = new ActiveDataProvider([

            'query' => $query, 'sort' => ['enableMultiSort' => true],

        'sort'=> ['defaultOrder' => ['Tender_No'=>SORT_DESC]]

        ]);

       // die(var_dump($dataProvider));

    $dataProvider->sort->attributes['Supplier_Name'] = [

        'asc' => ['supplier.Supplier_Name' => SORT_ASC],

        'desc' => ['Supplier.Supplier_name' => SORT_DESC],

        ];

    $dataProvider->sort->attributes['Tender_No'] = [

        'asc' => ['Supplier.Supplier_Name' => SORT_ASC],

        'desc' => ['Supplier.Supplier_Name' => SORT_DESC],

        ];

    $dataProvider->sort->attributes['Description'] = [

        'asc' => ['Description.Description' => SORT_ASC],

        'desc' => ['Description' => SORT_DESC],

        ];

   

        $this->load($params);


        if (!$this->validate()) {

            // uncomment the following line if you do not want to return any records when validation fails

            // $query->where('0=1');

            return $dataProvider;

        }


        $query->andFilterWhere([

            'Tender_No' => $this->Tender_No,

            'Description' => $this->Description,

            'Supplier_Name' => $this->Supplier_Name,

        ]);


        $query->andFilterWhere(['like', 'Tender_No', $this->Tender_No])

                ->andFilterWhere(['like', 'Supplier_Name', $this->Supplier_Name])

                ->andFilterWhere(['like', 'Description', $this->Description]);


        return $dataProvider;        

    }




    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
   
}
