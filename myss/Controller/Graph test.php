<?php
/**
 * Created by PhpStorm.
 * User: Simastir_da
 * Date: 25/03/2019
 * Time: 22:58
 */

ArangoDB arango = new ArangoDB.Builder().build();
ArangoDatabase db = arango.db("myDB");

EdgeDefinition edgeDefinition = new EdgeDefinition()
    .collection("edges")
    .from("start-vertices")
    .to("end-vertices");
GraphEntity graph = db.createGraph(
        "some-graph", Arrays.asList(edgeDefinition), new GraphCreateOptions()
    );