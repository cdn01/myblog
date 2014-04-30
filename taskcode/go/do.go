package main

import (
	"bytes"
	"encoding/xml"
	"fmt"
	"io/ioutil"
	"math/rand"
	"os"
	//"runtime"
	//"strconv"
	"os/exec"
	"time"
)

var SleepTime = 30000 * time.Millisecond
var PhpSleepTime = 5000 * time.Millisecond

type Dos struct {
	Count  int       `xml:"count"`
	Target []Targets `xml:"target"`
}
type Targets struct {
	Max     int    `xml:"max"`
	Current int    `xml:"current"`
	Rand    int    `xml:"rand"`
	Isrand    int    `xml:"isrand"`
	Action  string `xml:"action"`
}

var c = make(chan int )
func (d Dos) Exctask(key int) {
	<-c
	if d.Target[key].Isrand == 1{

		if d.Target[key].Current < d.Target[key].Rand {
			d.Target[key].Current++
		} else {
			d.Target[key].Current = 0
			d.Target[key].Rand = rand.Intn(d.Target[key].Max)
			cmd := exec.Command("php", d.Target[key].Action)
			time.Sleep(PhpSleepTime)
			var out bytes.Buffer
			cmd.Stdout = &out
			err := cmd.Run()
			if err != nil {
				fmt.Println("excu wrong~")
			}
			
		}

	}else{
		if d.Target[key].Current < d.Target[key].Max {
			d.Target[key].Current++
		} else {
			d.Target[key].Current = 0
			cmd := exec.Command("php", d.Target[key].Action)
			time.Sleep(PhpSleepTime)
			var out bytes.Buffer
			cmd.Stdout = &out
			err := cmd.Run()
			if err != nil {
				fmt.Println("excu wrong~")
			}
		}
	}
	
	fmt.Println(d.Target[key])
}
func main() {
	file, err := os.Open("do.xml") // For read access.
	if err != nil {
		fmt.Printf("open do.xml error: %v", err)
		return
	}
	defer file.Close()
	data, err := ioutil.ReadAll(file)
	if err != nil {
		fmt.Printf("read do.xml error: %v", err)
		return
	}
	doxml := Dos{}
	err = xml.Unmarshal(data, &doxml)
	if err != nil {
		fmt.Printf("error: %v", err)
		return
	}
	for {
		for key, _ := range doxml.Target {
			go doxml.Exctask(key)
			c <- 0
		}
		fmt.Println(doxml)
		fmt.Println("======================================================",time.Now().Format("2006-01-02 15:04:05"))
		time.Sleep(SleepTime)
	}

}
