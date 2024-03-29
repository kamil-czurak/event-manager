<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\EventTaskToStaff;

/**
 * EventTaskToStaffSearch represents the model behind the search form of `common\models\EventTaskToStaff`.
 */
class EventTaskToStaffSearch extends EventTaskToStaff
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['task_to_staff_id', 'task_id', 'staff_id', 'created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = EventTaskToStaff::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'task_to_staff_id' => $this->task_to_staff_id,
            'task_id' => $this->task_id,
            'staff_id' => $this->staff_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        return $dataProvider;
    }
}
