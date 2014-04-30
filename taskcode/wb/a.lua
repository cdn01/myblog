--[[贺卡传情
   1.贺卡赠送对象：a.可以自己输入玩家名称 b.选择随机在线用户
   2.送卡人福利 ：累计送卡数量,获得设定奖励
   3.收卡人动作 ： a.	每天只可查阅N张卡片(N第二天重置) b.可回赠贺卡--]]

local base=require "huodong_base.excuteBase"
local basesetting=require "huodong_base.settings"
local mysqlManager=require "huodong_base.mysqlmanager"
local resultCreator=require "huodong_base.result"
local utils=require "huodong_base.utils"
local check = require "huodong_base.inputcheck"
local cjson = require "cjson"
local christmascardSettings = require "ch_christmas_settings.christmascard_settings"
local errmsgs=require "huodong_errmsgs.traditionalch"
local slt2 = require "huodong_slt2.slt2"
local helpers = require "huodong_ngxs.helpers"
 
local db,method,postargs,getargs = base.run(nil)



function getRandPlayer()
	return true
end

--检验用户活动权限

function checkPlayerAccess(zoneid,playerid)
	local strfomat = string.format("zoneid=%03d&serialnumber=%d&action=%d&playerid=%d&param1=%s&param2=%s&param3=%s&param4=%s&param5=%s&time=%d"
		,zoneid
		,utils.serialnumber()
		,51
		,playerid
		,0
		,0
		,0
		,0
		,0
		,os.time()
	)
	local postdata= "?"..strfomat.."&sign="..utils.md5(strfomat..basesetting.gmkey) 
	local requsetres = helpers.capture.Get(basesetting.gmurl..postdata); 
	 
	if requsetres.status==200 and requsetres.body~="\n"  and  #requsetres.body ~= 0 then --判断有没有提交成功 
		return true
	else
		ngx.say(resultCreator.NewResult(1,nil,nil,"等级不够,不能参与活动"))
		ngx.exit(403)
		return false
	end 
end

--送贺卡
--参数:发送者send_playerid,接收者receive_playerid,贺卡类型cardtype
--流程:检测是否符合 

function sendCard(send_playerid,receive_playerid, card_type ,zoneid ,db) 
	local card = {[1]="acard",[2]="bcard",[3]="ccard"}
	local sql = "update christmascard_player set "..card[tonumber(card_type)].."= "..card[tonumber(card_type)].."-1 , sendnum = sendnum +1 where playerid= '{1}'  ";
	local err = mysqlManager.exec(db,sql,send_playerid) 
	local sql = "insert into  christmascard_info (playerid,zoneid,cardtype,sendplayerid) values ('{1}','{2}','{3}','{4}')"
	err = mysqlManager.exec(db,sql,receive_playerid,zoneid,card_type,send_playerid)
	if err.state == 5 then
		ngx.say(resultCreator.NewResult(1,nil,nil,"贺卡发送失败"))
		ngx.exit(403)
	end
	return true
end

--贺卡随机生成礼品
function getGift(cardGift) 
	 for _,v in ipairs(cardGift) do
        ngx.say(v.per)
    end
    local res = utils.getrandomIdx(cardGift) --抽奖结果 
    return res
end


if method == "POST" then
	
	local okspid,send_playerid = check.checkIsNum(postargs["send_playerid"],0)
	local okzoneid,zoneid=check.checkIsNum(postargs["zoneid"],0)
	
	--检验用户参加活动条件
	if checkPlayerAccess(zoneid,send_playerid) then 
		local okrpid,rec_playerid=check.checkIsNum(postargs["rec_playerid"],0)
		local action = postargs["action"]
		local card_type = postargs["cardtype"]
		if  not okspid  or not okzoneid or not action then
			ngx.say(resultCreator.NewResult(1,nil,nil,"參數不正確"))
			ngx.exit(403)
		end
		
		--返回一个在线随机用户playerid和用户名
		if action == "getranduser" then  
			--调用GM 取出当前在线所有玩家 进行随机抽取一个
			local strfomat = string.format("zoneid=%03d&serialnumber=%d&action=%d&playerid=%d&param1=%s&param2=%s&param3=%s&param4=%s&param5=%s&time=%d"
		,zoneid
		,utils.serialnumber()
		,37
		,send_playerid
		,0
		,0
		,0
		,0
		,0
		,os.time()
		)
		local postdata= "?"..strfomat.."&sign="..utils.md5(strfomat..basesetting.gmkey) 
		local requsetres = helpers.capture.Get(basesetting.gmurl..postdata); 
		
		ngx.say(cjson.encode(requsetres))
		if requsetres.status==200 and requsetres.body~="\n"  and  #requsetres.body ~= 0 then --判断有没有提交成功 
			return true
		else
			ngx.say(resultCreator.NewResult(1,nil,nil,"等级不够,不能参与活动"))
			ngx.exit(403)
			return false
		end 
		--随机用户end	
			
			
		--发送贺卡begin
		elseif action == "sendcard" then 
		
			if send_playerid == rec_playerid then
				ngx.say(resultCreator.NewResult(1,nil,nil,"贺卡不能自己送自己"))
		    	ngx.exit(403)
			end
			
			--检验发卡者送卡条件 卡片数量是否大于1
			local sql = "select playerid,zoneid , acard,bcard,ccard,opennum,opentime  from christmascard_player where  playerid = '{1}' and zoneid= '{2}' ;"
			local query=mysqlManager.query
		    local rs = query(db,sql,1
		          ,send_playerid
		          ,zoneid)  
			
			if rs and #rs > 0 then --有记录  
			
				--检验卡片数量
				if 	 card_type == "1" and rs[1]["acard"] < 1  then 
						ngx.say(resultCreator.NewResult(1,nil,nil,"您没有可用A类贺卡TT,快去参加挑战和抽奖活动吧"))
		    			ngx.exit(403)
				elseif 	card_type == "2" and rs[1]["bcard"] < 1 then
						ngx.say(resultCreator.NewResult(1,nil,nil,"您没有可用B类贺卡TT,快去参加挑战和抽奖活动吧"))
		    			ngx.exit(403) 	    			
				elseif  card_type == "3" and rs[1]["ccard"] < 1 then
						ngx.say(resultCreator.NewResult(1,nil,nil,"您没有可用C类贺卡TT,快去参加挑战和抽奖活动吧"))
		    			ngx.exit(403) 
				end
				
				--检验接收者条件是否符合
				if checkPlayerAccess(zoneid,rec_playerid) then
					sendCard(send_playerid,rec_playerid, card_type ,zoneid ,db)
					ngx.say(resultCreator.NewResult(0,nil,nil,"贺卡发送成功"))
				else
					ngx.say(resultCreator.NewResult(1,nil,nil,"对方等级不够,无法接收您的贺卡"))
				end
				
		    else
		    	ngx.say(resultCreator.NewResult(1,nil,nil,"您没有可用贺卡TT,快去参加挑战和抽奖活动吧"))
		    	ngx.exit(403)
		    end
		     
			mysqlManager.close(db) 
		
		--发送贺卡end


		--查看贺卡begin	
		elseif action == "opencard" then 
			local okcardid,cardid=check.checkIsNum(postargs["cardid"],0)
			
			--非法卡id
			if not okcardid then
				ngx.say(resultCreator.NewResult(1,nil,nil,"参数错误1"))
		    	ngx.exit(403)
			end 
			local sql = "call openchristmascard ('{1}','{2}','{3}','{4}',@status);select @status;";
			local query=mysqlManager.query
		    local rs = query(db,sql,1
		          ,send_playerid
		          ,cardid
		          ,zoneid
		          ,christmascardSettings.opencardmax)    
		          
		          
		    if rs[1]["@status"] == "1" then  --参数错误
		    	ngx.say(resultCreator.NewResult(1,nil,nil,"参数错误2")) 
		    elseif  rs[1]["@status"] == "2" then  --查看数量超过限制 
		    	ngx.say(resultCreator.NewResult(1,nil,nil,"一天最多只能查阅"..christmascardSettings.opencardmax.."封贺卡"))
	    	elseif  rs[1]["@status"] == "3" then  --可以查看
	    		--根据卡片类型 和 礼包配置 生成贺卡礼品
	    		local card_gift = christmascardSettings.cardGift[tonumber(card_type)]
	    		local gift_index = getGift(card_gift)
	    		ngx.say(card_gift[tonumber(gift_index)]["type"])
--	    		local gift = getGift()
	    		ngx.say(resultCreator.NewResult(0,nil,nil,"ok")) 
		    end
			mysqlManager.close(db)
			 
		--查看贺卡end
		
		end
	
	end
	local html = assert(slt2.loadfile(christmascardSettings.template..'christmascard.html'))
    data = {static=christmascardSettings.static,status=1}
    ngx.say(slt2.render(html,{data=data}))
end
































