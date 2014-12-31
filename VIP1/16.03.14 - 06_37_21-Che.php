<?php exit() ?>--by Che 91.8.190.152
--[[       ----------------------------------------------------------------------------------------------       ]]--
--[[										AquaPrisonMaster by Bilbao		   				 	                ]]--
--[[       ----------------------------------------------------------------------------------------------       ]]--
--[[	----------	]]--
--[[	15.03.2014	]]--
--[[	v. 1.0		]]--
--[[	----------	]]--


	if myHero.charName ~= "Nami" then return end
	if not VIP_USER then return end
	
	
	require "VPrediction"
	require "AoESkillshotPosition"

	local myVer = "1.0"

	local Qrange, Qwidth, Qspeed, Qdelay = 850, 200, 1750, 950
	local Wrange, WBouncerange, Wspeed  = 725, 375, 1100
	local Erange = 800
	local Rrange, Rwidth, Rspeed, Rdelay = 2750, 600, 1200, 0
	local QReady, WReady, EReady, RReady = false, false, false, false
	local QMana, WMana, EMana, RMana = false, false, false, false
	local AArange = 548
	local lastAttack, lastWindUpTime, lastAttackCD = 0, 0, 0
	local WHeal = 0
	local allyTable
	local enemyTable
	local target, healtarget, bufftarget, orbtarget, scantarget = nil, nil, nil, nil, nil
	local qts, wts, rts, ots
	local chtarget, chhealtarget, chbufftarget, chorbtarget, chscantarget =  nil, nil, nil, nil, nil
	local wayPointManager = WayPointManager()	
	local recall_status = false	
	local ch = {tick=0, spriteG=nil, spriteR=nil, trans=0, status=false }	
	local VP = nil		
	local abilitylvl = 0
	local lvlsequence = 1 

--[[       ----------------------------------------------------------------------------------------------       ]]--
--[[												OnX Functions			   				 	                ]]--
--[[       ----------------------------------------------------------------------------------------------       ]]--
function OnLoad()
	VP = VPrediction()
	_initmenu()
	_loadTS()
	_loadcrosshair()
	PrintChat("<font color='#03dafb'> >> AquaPrisonMaster v"..myVer.." by Bilbao loaded. </font>")
	_G.VPredictionMenu.Mode = 2
end


function OnTick()
	if recall_status then return end	
	_update()
	_caster()
	_autoskill()
	_crosshaircalc()
	_OrbWalk()
end


function OnDraw()
	_drawCH()
	_draw_ranges()
	_drawWP()
end


function OnCreateObj(object)
	if object.name == "TeleportHome.troy" and GetDistance(player, object) < 25 then
		recall_status = true
	end
	if object.name == "TeleportHomeImproved.troy" and GetDistance(player, object) < 25 then
		recall_status = true
	end	
end


function OnDeleteObj(object)
	if object.name == "TeleportHome.troy" and GetDistance(player, object) < 25 then
		recall_status = false 
	end
	if object.name == "TeleportHomeImproved.troy" and GetDistance(player, object) < 25 then
		recall_status = false 
	end
end


function OnProcessSpell(object, spell)
	if object == myHero then
		if spell.name:lower():find("attack") then
			lastAttack = GetTickCount() - GetLatency()/2
			lastWindUpTime = spell.windUpTime*1000
			lastAttackCD = spell.animationTime*1000
		end 
	end
end


--[[       ----------------------------------------------------------------------------------------------       ]]--
--[[												General Functions		   				 	                ]]--
--[[       ----------------------------------------------------------------------------------------------       ]]--


function _update()
	qts:update()
	wts:update()
	rts:update()
	ots:update()
	_spellcheck()
	_manacheck()
	_calcWHeal()	
	if Menu.extra.gadg.incQrange then
		Qrange = 945
	else
		Qrange = 850
	end	
end
		

function _caster()
	if (Menu.keys.permrota or Menu.keys.okdrota) then
		_smartcore()--on work
	end
	if (Menu.keys.permhrs or Menu.keys.okdhrs) then	
		_harrass()
	end
end


function _autoskill()
	if not Menu.extra.alvl.alvlstatus then return end	
	if myHero.level > abilitylvl then
		abilitylvl = abilitylvl + 1
		if Menu.extra.alvl.lvlseq == 1 then			
			LevelSpell(_R)
			LevelSpell(_Q)
			LevelSpell(_W)
			LevelSpell(_E)
		end
		if Menu.extra.alvl.lvlseq == 2 then	
			LevelSpell(_R)
			LevelSpell(_Q)
			LevelSpell(_E)
			LevelSpell(_W)
		end
		if Menu.extra.alvl.lvlseq == 3 then	
			LevelSpell(_R)
			LevelSpell(_W)
			LevelSpell(_Q)
			LevelSpell(_E)
		end
		if Menu.extra.alvl.lvlseq == 4 then	
			LevelSpell(_R)
			LevelSpell(_W)
			LevelSpell(_E)
			LevelSpell(_Q)
		end
		if Menu.extra.alvl.lvlseq == 5 then	
			LevelSpell(_R)
			LevelSpell(_E)
			LevelSpell(_Q)
			LevelSpell(_W)
		end
		if Menu.extra.alvl.lvlseq == 6 then	
			LevelSpell(_R)
			LevelSpell(_E)
			LevelSpell(_W)
			LevelSpell(_Q)
		end
	end
end


function _OrbWalk()
	local letmeorb = false	
	if Menu.ta.mota.orb == 1 then
		if (Menu.keys.permrota or Menu.keys.okdrota or Menu.keys.permhrs or Menu.keys.okdhrs) then
			letmeorb = true			
		end
	end
	if Menu.ta.mota.orb == 2 then	
		letmeorb = true
	end	
	if Menu.ta.mota.orb == 3 then
		letmeorb = false
	end	
	if not letmeorb then return end
	
	if target~=nil then
		if GetDistance(target) <= AArange then
			orbtarget = target
		else
			orbtarget = nil
		end
	else
		orbtarget = nil
	end
	if orbtarget==nil then
		orbtarget = _orbwalktarget()
	end	
	chorbtarget = orbtarget
	if orbtarget~=nil and GetDistance(orbtarget) <= AArange then
		chorbtarget = orbtarget
		if timeToShoot() then
			myHero:Attack(orbtarget)
		elseif heroCanMove()  then
			moveToCursor()
		end
	else		
		moveToCursor() 
	end
end


function _drawCH()
if not Menu.tartrack.single then return end
	if ch.spriteG and chtarget~=nil then	
		ch.spriteG:SetScale(0.4, 0.4)		
		local chPosG =  WorldToScreen(D3DXVECTOR3(chtarget.x, chtarget.y, chtarget.z))	   
		ch.spriteG:Draw(chPosG.x-30, chPosG.y-105, ch.trans)		
	end	
	if ch.spriteR and chscantarget~=nil then
		ch.spriteR:SetScale(0.4, 0.4)		
		local chPosR =  WorldToScreen(D3DXVECTOR3(chscantarget.x, chscantarget.y, chscantarget.z))	   
		ch.spriteR:Draw(chPosR.x-30, chPosR.y-105, 150)		
	end
	if ch.spriteO and chorbtarget~=nil then
		ch.spriteO:SetScale(0.2, 0.2)		
		local chPosO =  WorldToScreen(D3DXVECTOR3(chorbtarget.x, chorbtarget.y, chorbtarget.z))	   
		ch.spriteO:Draw(chPosO.x-30, chPosO.y-105, ch.trans)		
	end
end


function _drawWP()
	if chtarget~=nil and ValidTarget(chtarget, 2500) then
		if Menu.draw.drawsub.drawwp then
			wayPointManager:DrawWayPoints(chtarget)
		end
		if Menu.draw.drawsub.drawwpr then
			DrawText3D(tostring(wayPointManager:GetWayPointChangeRate(chtarget)), chtarget.x, chtarget.y, chtarget.z, 30, ARGB(250,5,250,5), true)
		end
	end
end


function _draw_tarvisu()
	if chtarget~=nil and ValidTarget(chtarget, 2500) then	
		if Menu.draw.tarvisu.drawcltar then		
			DrawLine3D(myHero.x, myHero.y, myHero.z, chtarget.x, chtarget.y, chtarget.z, 1, ARGB(250,235,33,33))
		end	
		if Menu.draw.tarvisu.drawcltar then
			DrawCircle(chtarget.x, chtarget.y, chtarget.z, 100, ARGB(250, 253, 33, 33))
		end
	end
end


function _draw_ranges()
	if Menu.draw.drawsub2.drawaa then
		DrawCircle(myHero.x, myHero.y, myHero.z, AArange, ARGB(25 , 125, 125, 125))
	end
	if Menu.draw.drawsub2.drawQ and QReady then
		DrawCircle(myHero.x, myHero.y, myHero.z, Qrange, ARGB(100, 0, 0, 250))
	end
	if Menu.draw.drawsub2.drawW and WReady then
		DrawCircle(myHero.x, myHero.y, myHero.z, Wrange, ARGB(100, 250, 0, 250))
	end
	if Menu.draw.drawsub2.drawE and EReady then
		DrawCircle(myHero.x, myHero.y, myHero.z, Erange, ARGB(100, 0, 250, 0))
	end
end


--[[       ----------------------------------------------------------------------------------------------       ]]--
--[[					     	 		     	     Utility			   	      								]]--
--[[       ----------------------------------------------------------------------------------------------       ]]--


function _spellcheck()
    QReady = (myHero:CanUseSpell(_Q) == READY)
    WReady = (myHero:CanUseSpell(_W) == READY)
    EReady = (myHero:CanUseSpell(_E) == READY)
    RReady = (myHero:CanUseSpell(_R) == READY)
end


function _manacheck()
	if Menu.manamana.manaQ then QMana =  _manaQ() else QMana = true end
	if Menu.manamana.manaW then WMana =  _manaW() else WMana = true end
	if Menu.manamana.manaE then EMana =  _manaE() else EMana = true end
	if Menu.manamana.manaR then RMana =  _manaR() else RMana = true end
end


function _manaQ()
    if myHero.mana < (myHero.maxMana * ( Menu.manamana.manamanaQ / 100)) then
        return true
    else
        return false
    end
end


function _manaW()
    if myHero.mana < (myHero.maxMana * ( Menu.manamana.manamanaW / 100)) then
        return true
    else
        return false
    end
end


function _manaE()
    if myHero.mana < (myHero.maxMana * ( Menu.manamana.manamanaE / 100)) then
        return true
    else
        return false
    end
end


function _manaR()
    if myHero.mana < (myHero.maxMana * ( Menu.manamana.manamanaR / 100)) then
        return true
    else
        return false
    end
end


function _calcWHeal()
local myWlvl = myHero:GetSpellData(_W).level
local myWbasicHeal = 0
local myAPBonus = myHero.ap*0.3
	if myWlvl==1 then
		myWbasicHeal = 65
		elseif myWlvl==2 then
			myWbasicHeal = 95
			elseif myWlvl==3 then
				myWbasicHeal = 125
				elseif myWlvl==4 then
					myWbasicHeal = 155
					elseif myWlvl==5 then
					myWbasicHeal = 185
	end	
if myWlvl==1 or myWlvl==2 or myWlvl==3 or myWlvl==4 or myWlvl==5 then
	WHeal = myAPBonus + myWbasicHeal
end
end


function _harrass()
	if Menu.harrass.hrsQ and QMana and QReady then
		local myQtar = nil	
		if Menu.ta.mqta.qta == 1 then 
			myQtar = qts.target
		end	
		if Menu.ta.mqta.qta == 2 then
			local enemyNearAdc = nil
			local myAdc = _getallymostad(Qrange)
			enemyNearAdc = _getenemynear(myAdc)
			if enemyNearAdc ~= nil and myHero:GetDistance(enemyNearAdc) < Qrange and myAdc~=nil and myAdc:GetDistance(enemyNearAdc) < Menu.ta.mqta.qtamin then
				myQtar = enemyNearAdc
			else
				myQtar = qts.target				
			end	
		end
		if Menu.ta.mqta.qta == 3 then
		local enemyNearApc = nil
		local myApc = _getallymostap(Qrange)
			enemyNearApc = _getenemynear(myApc)
			if enemyNearApc ~= nil and myHero:GetDistance(enemyNearApc) < Qrange and myApc~=nil and myApc:GetDistance(enemyNearApc) < Menu.ta.mqta.qtamin  then
				myQtar = enemyNearApc
			else				
				myQtar = qts.target
			end	
		end
		if Menu.ta.mqta.qta == 4 then
		local enemyNearAPCADC = nil
			enemyNearAPCADC = _enemynearadcorapc(Qrange)
			if enemyNearAPCADC ~= nil and myHero:GetDistance(enemyNearAPCADC) < Menu.ta.mqta.qtamin then
				myQtar = enemyNearAPCADC
			else				
				myQtar = qts.target
			end	
		end
		if Menu.ta.mqta.qta == 5 then
		local bestEnemy = _getenemywithbesthitchance(Qrange, Qwidth, Qspeed, Qdelay)
		if bestEnemy ~= nil and myHero:GetDistance(bestEnemy) < Qrange and not bestEnemy.dead then
			myQtar = bestEnemy
		end	
		end
		if Menu.ta.mqta.qta == 6 then
		local bestPosByMec = _getMostEnemyIn(Qrange, Qwidth, Qdelay)
		if bestPosByMec ~= nil then
			myQtar = bestPosByMec
		end
	end	
	chtarget = myQtar
	if myQtar~=nil  then	
		if Menu.ta.mqta.qta <= 5 then		
			if myHero:GetDistance(myQtar) < Qrange then			
				CastSpell(_Q, myQtar.x, myQtar.z)
			end
		else
			if Menu.ta.mqta.qta == 6 then
				if Menu.extra.gadg.incQrange then
					--cast q auf erhöhte range
					local incQpos = myQtar
					if GetDistance(incQpos) <= (Qrange) then
						local incrQpos = Vector(myHero) + (Vector(incQpos) - Vector(myHero)):normalized() * Qrange
						if extended_QPos then
							Packet('S_CAST', { spellId = _Q, fromX = incrQpos.x, fromY = incrQpos.z}):send()
						end
					end
				else
					CastSpell(_Q, myQtar.x, myQtar.z)
				end
			end
		end
	end	
	end
	if Menu.harrass.hrsW and WMana and WReady then
		local myWtar = nil	
		if Menu.ta.mwta.wta == 1 then 
			myWtar = wts.target
		end
		if Menu.ta.mwta.wta == 2 then 
			local mTwoDmgTar = _mostwdmgtarget()			
			if mTwoDmgTar then			
				myWtar = mTwoDmgTar
			else
				local mTwoHealTar = _bestHealTar()
				myWtar = mTwoHealTar
			end
		end
		if Menu.ta.mwta.wta == 3 then 
			local mTwoHealTar = _bestHealTar()
			if mTTwoHealTar then
				myWtar = mTTwoHealTar
			else
				local mTTwoDmgTar = _mostwdmgtarget()
				myWtar = mTTwoDmgTar
			end			
		end
		if Menu.ta.mwta.wta == 4 then 
		local myADC = _getallymostad(Wrange)
			if myADC.health + Wheal <= myADC.maxHealth then
				myWtar = myADC
			else
				myWtar = wts.target
			end		
		end
		if Menu.ta.mwta.wta == 5 then 
		local myAPC = _getallymostap(Wrange)
			if myAPC.health + Wheal <= myAPC.maxHealth then
				myWtar = myAPC
			else
				myWtar = wts.target
			end		
		end
		if Menu.ta.mwta.wta == 6 then 
			local myaDc, myaPc = nil, nil
			local myaDcHP, myaPcHP = 100, 100
			myaDc = _getallymostad(Wrange)
			myaPc = _getallymostap(Wrange)			
			if myaDc~=nil then myaDcHP = ((myaDc.health/myaDc.maxHealth)*100) end
			if myaPc~=nil then myaPcHP = ((myaPc.health/myaPc.maxHealth)*100) end			
			if myaDcHP+Wheal > myaPcHP+WHeal then
				myWtar = myaPc
			else
				myWtar = myaDc
			end		
		end
		if Menu.ta.mwta.wta == 7 then 
			local myaDc, myaPc = nil, nil
			local myaDcHP, myaPcHP = 100, 100
			myaDc = _getallymostad(Wrange)
			myaPc = _getallymostap(Wrange)			
			if myaDc~=nil then myaDcHP = ((myaDc.health/myaDc.maxHealth)*100) end
			if myaPc~=nil then myaPcHP = ((myaPc.health/myaPc.maxHealth)*100) end			
			if myaDcHP+Wheal < myaPcHP+WHeal then
				myWtar = myaPc
			else
				myWtar = myaDc
			end		
		end		
		if Menu.ta.mwta.wta == 8 then 
			local mAHealTar = _bestHealTar()
			myWtar = mAHealTar			
		end		
		if Menu.ta.mwta.wta == 9 then 
			local mNHealTar = _bestHealTarTotal()
			myWtar = mNHealTar			
		end
		chtarget = myWtar
		if myWtar~=nil then		
			if Menu.extra.gadg.wnfe then
				
				_sendnfW(myWtar)
			else
				CastSpell(_W, myWtar)
			end
		end
	end
end


function _smartcore()
if Menu.rota.useQ and QMana and QReady then
	local myQtar = nil	
	if Menu.ta.mqta.qta == 1 then 
		myQtar = qts.target
	end	
	if Menu.ta.mqta.qta == 2 then
		local enemyNearAdc = nil
		local myAdc = _getallymostad(Qrange)
			enemyNearAdc = _getenemynear(myAdc)
			if enemyNearAdc ~= nil and myHero:GetDistance(enemyNearAdc) < Qrange and myAdc~=nil and myAdc:GetDistance(enemyNearAdc) < Menu.ta.mqta.qtamin then
				myQtar = enemyNearAdc
			else
				myQtar = qts.target				
			end	
	end
	if Menu.ta.mqta.qta == 3 then
		local enemyNearApc = nil
		local myApc = _getallymostap(Qrange)
			enemyNearApc = _getenemynear(myApc)
			if enemyNearApc ~= nil and myHero:GetDistance(enemyNearApc) < Qrange and myApc~=nil and myApc:GetDistance(enemyNearApc) < Menu.ta.mqta.qtamin  then
				myQtar = enemyNearApc
			else				
				myQtar = qts.target
			end	
	end
	if Menu.ta.mqta.qta == 4 then
		local enemyNearAPCADC = nil
			enemyNearAPCADC = _enemynearadcorapc(Qrange)
			if enemyNearAPCADC ~= nil and myHero:GetDistance(enemyNearAPCADC) < Menu.ta.mqta.qtamin then
				myQtar = enemyNearAPCADC
			else				
				myQtar = qts.target
			end	
	end
	if Menu.ta.mqta.qta == 5 then
		local bestEnemy = _getenemywithbesthitchance(Qrange, Qwidth, Qspeed, Qdelay)
		if bestEnemy ~= nil and myHero:GetDistance(bestEnemy) < Qrange and not bestEnemy.dead then
			myQtar = bestEnemy
		end	
	end
	if Menu.ta.mqta.qta == 6 then
	local bestPosByMec = _getMostEnemyIn(Qrange, Qwidth, Qdelay)
		if bestPosByMec ~= nil then
			myQtar = bestPosByMec
		end
	end		
	chtarget = myQtar
	if myQtar~=nil  then	
		if Menu.ta.mqta.qta <= 5 then		
			if myHero:GetDistance(myQtar) < Qrange then			
				CastSpell(_Q, myQtar.x, myQtar.z)
			end
		else
			if Menu.ta.mqta.qta == 6 then
				if Menu.extra.gadg.incQrange then
					--cast q auf erhöhte range
					local incQpos = myQtar
					if GetDistance(incQpos) <= (Qrange) then
						local incrQpos = Vector(myHero) + (Vector(incQpos) - Vector(myHero)):normalized() * Qrange
						if extended_QPos then
							Packet('S_CAST', { spellId = _Q, fromX = incrQpos.x, fromY = incrQpos.z}):send()
						end
					end
				else
					CastSpell(_Q, myQtar.x, myQtar.z)
				end
			end
		end
	end	
end


if Menu.rota.useW and WMana and WReady then
local myWtar = nil	
		if Menu.ta.mwta.wta == 1 then 
			myWtar = wts.target
		end
		if Menu.ta.mwta.wta == 2 then 
			local mTwoDmgTar = _mostwdmgtarget()			
			if mTwoDmgTar then			
				myWtar = mTwoDmgTar
			else
				local mTwoHealTar = _bestHealTar()
				myWtar = mTwoHealTar
			end
		end
		if Menu.ta.mwta.wta == 3 then 
			local mTwoHealTar = _bestHealTar()
			if mTTwoHealTar then
				myWtar = mTTwoHealTar
			else
				local mTTwoDmgTar = _mostwdmgtarget()
				myWtar = mTTwoDmgTar
			end			
		end
		if Menu.ta.mwta.wta == 4 then 
		local myADC = _getallymostad(Wrange)
			if myADC.health + Wheal <= myADC.maxHealth then
				myWtar = myADC
			else
				myWtar = wts.target
			end		
		end
		if Menu.ta.mwta.wta == 5 then 
		local myAPC = _getallymostap(Wrange)
			if myAPC.health + Wheal <= myAPC.maxHealth then
				myWtar = myAPC
			else
				myWtar = wts.target
			end		
		end
		if Menu.ta.mwta.wta == 6 then 
			local myaDc, myaPc = nil, nil
			local myaDcHP, myaPcHP = 100, 100
			myaDc = _getallymostad(Wrange)
			myaPc = _getallymostap(Wrange)			
			if myaDc~=nil then myaDcHP = ((myaDc.health/myaDc.maxHealth)*100) end
			if myaPc~=nil then myaPcHP = ((myaPc.health/myaPc.maxHealth)*100) end			
			if myaDcHP+Wheal > myaPcHP+WHeal then
				myWtar = myaPc
			else
				myWtar = myaDc
			end		
		end
		if Menu.ta.mwta.wta == 7 then 
			local myaDc, myaPc = nil, nil
			local myaDcHP, myaPcHP = 100, 100
			myaDc = _getallymostad(Wrange)
			myaPc = _getallymostap(Wrange)			
			if myaDc~=nil then myaDcHP = ((myaDc.health/myaDc.maxHealth)*100) end
			if myaPc~=nil then myaPcHP = ((myaPc.health/myaPc.maxHealth)*100) end			
			if myaDcHP+Wheal < myaPcHP+WHeal then
				myWtar = myaPc
			else
				myWtar = myaDc
			end		
		end		
		if Menu.ta.mwta.wta == 8 then 
			local mAHealTar = _bestHealTar()
			myWtar = mAHealTar			
		end		
		if Menu.ta.mwta.wta == 9 then 
			local mNHealTar = _bestHealTarTotal()
			myWtar = mNHealTar			
		end
		chtarget = myWtar
		if myWtar~=nil then		
			if Menu.extra.gadg.wnfe then
				
				_sendnfW(myWtar)
			else
				CastSpell(_W, myWtar)
			end
		end	
end
if Menu.rota.useE and EMana and EReady then
	local myEtar = nil				
			if (Menu.ta.meta.eta == 1 or Menu.ta.meta.eta == 2 or Menu.ta.meta.eta == 5) then 
				local myADC = _getallymostad(Erange)				
				myEtar = myADC
			end
			if Menu.ta.meta.eta == 3 then 			
				local myAS = _getallymostas(Erange)
				myEtar = myAS
			end
			if Menu.ta.meta.eta == 4 then
			local AllyCloseToEnemy = _getAllyNearEnemy(Erange)
				myEtar = AllyCloseToEnemy			
			end
			if Menu.ta.meta.eta == 6 then
				local myAPC = _getallymostap(Erange)
				myEtar = myAPC			
			end
			if Menu.ta.meta.eta == 7 then 
				local lowhpP = _bestHealTar()
				myEtar = lowhpP
			end
			if Menu.ta.meta.eta == 8 then 
				local lowhpT = _bestHealTarTotal()
				myEtar = lowhpT
			end
		if myEtar~=nil then
			CastSpell(_E, myEtar)
		end	
end


if Menu.rota.useR and RMana and RReady then
	local myRpos = nil	
	if Menu.ta.mrta.rta == 1 then 		
		myRpos = rts.target		
	end
	if Menu.ta.mrta.rta == 2 then 		
		local myADC = _getallymostad(Rrange)
		if myADC~=nil then
			local nexentoadc = _getenemynear(myADC)
			if myADC:GetDistance(nexentoadc) < Menu.ta.mrta.rtamin then
				myRpos = nexentoadc
			end			
		end		
	end
	if Menu.ta.mrta.rta == 3 then 		
		local myAPC = _getallymostap(Rrange)
		if myAPC~=nil then
			local nexentoapc = _getenemynear(myAPC)
			if myAPC:GetDistance(nexentoapc) < Menu.ta.mrta.rtamin then
				myRpos = nexentoapc
			end			
		end		
	end
	if Menu.ta.mrta.rta == 4 then
		local adDis, apDis = 50000, 50000
		local myADC = _getallymostad(Rrange)
		if myADC~=nil then
			local nexentoadc = _getenemynear(myADC)
			if myADC:GetDistance(nexentoadc) < Menu.ta.mrta.rtamin then
				adDis = myADC:GetDistance(nexentoadc)
			end			
		end	
		local myAPC = _getallymostap(Rrange)
		if myAPC~=nil then
			local nexentoapc = _getenemynear(myAPC)
			if myAPC:GetDistance(nexentoapc) < Menu.ta.mrta.rtamin then
				apDis = myAPC:GetDistance(nexentoapc)
			end			
		end	
		if apDis < adDis then
			myRpos = nexentoapc
		else
			myRpos = nexentoadc
		end	
	end
	if Menu.ta.mrta.rta == 5 then
		local bestPosByMec = _getMostEnemyIn(Rrange, Rwidth, Rdelay)
		if bestPosByMec ~= nil then
			myRpos = bestPosByMec
		end	
	end	
	chtarget = myRpos
	if myRpos~=nil then
		CastSpell(_R, myRpos.x, myRpos.z)
	end	
end
end


function _getMostEnemyIn(range, width, delay)
	local besthitenemy = nil
	local bestcastpos = nil
	besthitenemy = _getenemywithbesthitchance(Qrange, Qwidth, Qspeed, Qdelay)
	if besthitenemy~=nil then
		bestcastpos = GetAoESpellPosition(350, besthitenemy)
	end
	return bestcastpos
end


function _getenemywithbesthitchance(range,width, speed, delay)
local bestTar, tarHit = nil, 0
   for i, vptarget in pairs(GetEnemyHeroes()) do
		chscantarget = vptarget
        local CastPosition,  HitChance,  Position = VP:GetCircularCastPosition(vptarget, delay, width, range, speed, myHero, false) --nicht linear,auf kreis anpassen		
        if HitChance >= 2 and GetDistance(CastPosition) <= range and ValidTarget(vptarget, range) and HitChance >=tarHit then				
			bestTar = vptarget
			tarHit = HitChance							
        end
	end
	return bestTar
end


function _mostwdmgtarget()
	local DmgOnTar, DmgTarUnit = 0, nil	
	for i, DmgTar in pairs(GetEnemyHeroes()) do	
		chscantarget = DmgTar
        if GetDistance(DmgTar) <= Wrange and not DmgTar.dead then
			local DmgTarTmpDmg = getDmg("W", DmgTar, myHero)
				if DmgTarTmpDmg >= DmgOnTar then
					DmgOnTar = DmgTarTmpDmg
					DmgTarUnit = DmgTar
				end				
        end
	end
	return DmgTarUnit
end


function _bestHealTar()
	local BestHealAlly, BestHealAllyHealth = nil, 100
	for i = 1, heroManager.iCount do
	local currHealTar = heroManager:GetHero(i)
		chscantarget = currHealTar
        if currHealTar.team==myHero.team and GetDistance(currHealTar) <= Wrange and not currHealTar.dead and currHealTar.health <= (currHealTar.maxHealth*(BestHealAllyHealth/ 100)) and currHealTar.health+WHeal <= currHealTar.maxHealth then
			BestHealAlly = currHealTar
			BestHealAllyHealth = ((currHealTar.health/currHealTar.maxHealth)*100)		
        end
	end
	return BestHealAlly
end


function _bestHealTarTotal()
	local BestHealAlly, BestHealAllyHealth = nil, 50000	
	for i = 1, heroManager.iCount do
		local currHealTar = heroManager:GetHero(i)
		chscantarget = currHealTar		
        if  currAlly.team==myHero.team and  GetDistance(currHealTar) <= Wrange and not currHealTar.dead and currHealTar.health <= BestHealAllyHealth and currHealTar.health+WHeal <= currHealTar.maxHealth then
			BestHealAlly = currHealTar
			BestHealAllyHealth = currHealTar.health		
        end
	end
	return BestHealAlly
end


function _getAllyNearEnemy(range)
local ThisAlly, ThisRange = nil, 50000
	for i = 1, heroManager.iCount do
		local currAlly = heroManager:GetHero(i)
		chscantarget = currAlly
			if  currAlly.team==myHero.team and  myHero:GetDistance(currAlly) <= range and not currAlly.dead then
			local enemyNearCurrAlly = _getenemynear(currAlly)
			local enemyDistToAlly = currAlly:GetDistance(enemyNearCurrAlly)
				if enemyDistToAlly <= ThisRange then
					ThisRange = enemyDistToAlly
					ThisAlly = currAlly				
				end			
			end		
		end
	return ThisAlly
end


function _getenemynear(target)
    local distance = 20000
    local closest = nil
	if target~=nil then
		for i=1, heroManager.iCount do
			currentEnemy = heroManager:GetHero(i)
			chscantarget = currentEnemy
			if currentEnemy~=nil and ValidTarget(currentEnemy, Qrange) and currentEnemy.team ~= myHero.team and not currentEnemy.dead and target:GetDistance(currentEnemy) <= distance then
				distance = target:GetDistance(currentEnemy)
				closest = currentEnemy
			end
		end
	end
    return closest
end


function _enemynearadcorapc(range)
local myADC = _getallymostad(range)
local myAPC = _getallymostap(range)
local enemyToADC, enemyToAPC = nil, nil
local enemyToADCrange, enemyToAPCrange = 50000
if myADC~=nil then
	enemyToADC = _getenemynear(myADC)
		if enemyToADC~=nil then
			enemyToADCrange = myADC:GetDistance(enemyToADC)
		end
end
if myAPC~=nil then
	enemyToAPC = _getenemynear(myAPC)
		if enemyToAPC~=nil then
			enemyToAPCrange = myAPC:GetDistance(enemyToAPC)
		end
end
if enemyToADCrange~=nil and enemyToAPCrange~=nil then
	if enemyToADCrange >= enemyToAPCrange then
		return enemyToAPC
	else
	return enemyToADC
	end
else
	return qts.target
end
end


function _getallymostad(range)
	local allyAD, allySelf = 0, nil
		for i = 1, heroManager.iCount do
		local currAlly = heroManager:GetHero(i)
		chscantarget = currAlly
        if  currAlly.team==myHero.team and GetDistance(currAlly) <= range and currAlly.dead==false and currAlly.addDamage >= allyAD then		
			allySelf = currAlly
			allyAD = currAlly.addDamage				
        end
	end
	return allySelf
end


function _getallymostas(range)
	local allyAS, allySelf = 0, nil
	for i = 1, heroManager.iCount do
		local currAlly = heroManager:GetHero(i)	
		chscantarget = currAlly		
        if currAlly.team==myHero.team and GetDistance(currAlly) <= range and currAlly.dead==false and currAlly.attackSpeed >= allyAS then				
			allySelf = currAlly
			allyAS = currAlly.attackSpeed				
        end
	end
	return allySelf
end


function _getallymostap(range)
	local allyAP, allySelf = 0, nil
	for i = 1, heroManager.iCount do
		local currAlly = heroManager:GetHero(i)		
		chscantarget = currAlly
        if  currAlly.team==myHero.team and GetDistance(currAlly) <= range and currAlly.dead==false and currAlly.ap >= allyAP then				
			allySelf = currAlly
			allyAP = currAlly.ap				
        end
	end
	return allySelf
end


function _crosshaircalc()
	if GetTickCount() >= ch.tick + 25 then
		if ch.trans == 255 then
			ch.status = false	
		end
		if ch.trans == 0 then
			ch.status = true
		end
		if ch.status then
			ch.trans = ch.trans + 51
		else
			ch.trans = ch.trans - 51
		end
		ch.tick = GetTickCount()
	end
end


function heroCanMove()
	return (GetTickCount() + GetLatency()/2 > lastAttack + lastWindUpTime + 20)
end
 
 
function timeToShoot()
	return (GetTickCount() + GetLatency()/2 > lastAttack + lastAttackCD)
end
 
 
function moveToCursor()
	if GetDistance(mousePos) > 1 or lastAnimation == "Idle1" then
		local moveToPos = myHero + (Vector(mousePos) - myHero):normalized()*300
		myHero:MoveTo(moveToPos.x, moveToPos.z)
	end 
end


function _orbwalktarget()
local orb_target_is = nil
	if Menu.ta.mota.ota == 1 then
		orb_target_is = ots.target
	end
	if Menu.ta.mota.ota == 2 then
		orb_target_is = _bestOrbAAtarget()		
	end	
	return orb_target_is
end


function OLD_orbwalktarget()
    local _orbtar = {tar=nil, hp=20000}	
    for i=1, heroManager.iCount do
        currentEnemy = heroManager:GetHero(i)
        if currentEnemy.team ~= myHero.team and currentEnemy.charName ~= myHero.charName and currentEnemy.visible and currentEnemy.bTargetable and currentEnemy~=nil then
            if not currentEnemy.dead and myHero:GetDistance(currentEnemy) <= AArange  and currentEnemy.health < _orbtar.hp then				
				_orbtar.hp = currentEnemy.health
				_orbtar.tar = currentEnemy			
            end
        end
    end
	return _orbtar.tar
end

function _bestOrbAAtarget()
	local DmgOnTar, DmgTarUnit = 0, nil	
	for i, DmgTar in pairs(GetEnemyHeroes()) do			
        if GetDistance(DmgTar) <= AArange-2 and not DmgTar.dead then
			local DmgTarTmpDmg = getDmg("AD", DmgTar, myHero)
				if DmgTarTmpDmg >= DmgOnTar then
					DmgOnTar = DmgTarTmpDmg
					DmgTarUnit = DmgTar
				end				
        end
	end
	return DmgTarUnit
end


function _sendnfW(nftarget)
	local Wpacket = CLoLPacket(153)
		Wpacket.dwArg1 = 1
		Wpacket.dwArg2 = 0
		Wpacket:EncodeF(myHero.networkID)
		Wpacket:Encode1(_W)
		Wpacket:EncodeF(nftarget.x)
		Wpacket:EncodeF(nftarget.z)
		Wpacket:EncodeF(myHero.x)
		Wpacket:EncodeF(myHero.z)
		Wpacket:EncodeF(nftarget.networkID)
		SendPacket(Wpacket)
end


--[[       ----------------------------------------------------------------------------------------------       ]]--
--[[					     	 		     	     Once				   	      								]]--
--[[       ----------------------------------------------------------------------------------------------       ]]--


function _initmenu()
	Menu = scriptConfig("Nami - AquaPrisonMaster", "NamibyBILBAO")
	
		-----------------------------------------------------------------------------------------------------
		Menu:addSubMenu("Drawing", "draw")
			Menu.draw:addSubMenu("WayPointManager","drawsub")
				Menu.draw.drawsub:addParam("drawwp", "Draw waypoints", SCRIPT_PARAM_ONOFF, true)
				Menu.draw.drawsub:addParam("drawwpr", "Draw waypoint rate", SCRIPT_PARAM_ONOFF, false)
		-----------------------------------------------------------------------------------------------------
			Menu.draw:addSubMenu("Target visualisation", "tarvisu")				
				Menu.draw.tarvisu:addParam("drawcltar", "Draw circle around target", SCRIPT_PARAM_ONOFF, false)
		-----------------------------------------------------------------------------------------------------
			Menu.draw:addSubMenu("Ranges", "drawsub2")
				Menu.draw.drawsub2:addParam("drawaa", "Draw AARange", SCRIPT_PARAM_ONOFF, true)
				Menu.draw.drawsub2:addParam("drawQ", "Draw QRange", SCRIPT_PARAM_ONOFF, true)
				Menu.draw.drawsub2:addParam("drawW", "Draw WRange", SCRIPT_PARAM_ONOFF, true)
				Menu.draw.drawsub2:addParam("drawE", "Draw ERange", SCRIPT_PARAM_ONOFF, true)
		-----------------------------------------------------------------------------------------------------
		
		-----------------------------------------------------------------------------------------------------
		Menu:addSubMenu("Harrass", "harrass")
			Menu.harrass:addParam("hrsQ", "Use Q", SCRIPT_PARAM_ONOFF, false)
			Menu.harrass:addParam("hrsW", "Use W", SCRIPT_PARAM_ONOFF, false)
		-----------------------------------------------------------------------------------------------------

		-----------------------------------------------------------------------------------------------------
		Menu:addSubMenu("Rotation", "rota")
			Menu.rota:addParam("useQ", "Use Q in Combo", SCRIPT_PARAM_ONOFF, true)
			Menu.rota:addParam("useW", "Use W in Combo", SCRIPT_PARAM_ONOFF, true)
			Menu.rota:addParam("useE", "Use E in Combo", SCRIPT_PARAM_ONOFF, true)
			Menu.rota:addParam("useR", "Use R in Combo", SCRIPT_PARAM_ONOFF, false)
		-----------------------------------------------------------------------------------------------------
		
		-----------------------------------------------------------------------------------------------------
		Menu:addSubMenu("Mana Manager", "manamana")
			Menu.manamana:addParam("manaQ", "Use Q with MM", SCRIPT_PARAM_ONOFF, false)
			Menu.manamana:addParam("manamanaQ", "Use Q until %", SCRIPT_PARAM_SLICE, 0, 0, 1000, 0)
			Menu.manamana:addParam("manaW", "Use W with MM", SCRIPT_PARAM_ONOFF, false)
			Menu.manamana:addParam("manamanaW", "Use W until %", SCRIPT_PARAM_SLICE, 0, 0, 1000, 0)
			Menu.manamana:addParam("manaE", "Use E with MM", SCRIPT_PARAM_ONOFF, false)
			Menu.manamana:addParam("manamanaE", "Use E until %", SCRIPT_PARAM_SLICE, 40, 0, 1000, 0)
			Menu.manamana:addParam("manaR", "Use R with MM", SCRIPT_PARAM_ONOFF, false)
			Menu.manamana:addParam("manamanaR", "Use R until %", SCRIPT_PARAM_SLICE, 0, 0, 1000, 0)
		-----------------------------------------------------------------------------------------------------
		
		-----------------------------------------------------------------------------------------------------
		Menu:addSubMenu("Hotkeys", "keys")		
			Menu.keys:addParam("permrota", "Auto Rotation", SCRIPT_PARAM_ONKEYTOGGLE, true, string.byte("S"))
			Menu.keys:permaShow("permrota")
			Menu.keys:addParam("okdrota", "OnKeyDown Rotation", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("A"))
			Menu.keys:permaShow("okdrota")		
			Menu.keys:addParam("permhrs", "Auto Harrass", SCRIPT_PARAM_ONKEYTOGGLE, false, string.byte("Z"))
			Menu.keys:permaShow("permhrs")
			Menu.keys:addParam("okdhrs", "OnKeyDown Harrass", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("T"))
			Menu.keys:permaShow("okdhrs")
		-----------------------------------------------------------------------------------------------------
		
		-----------------------------------------------------------------------------------------------------
		Menu:addSubMenu("Target acquisition", "ta")
		-----------------------------------------------------------------------------------------------------
			Menu.ta:addSubMenu("Q Target", "mqta")
				Menu.ta.mqta:addParam("qta", "Q Target", SCRIPT_PARAM_LIST, 5, { "Target Selector", "Prot. ADC>TS", "Prot. APC>TS", "Prot. APC/ADC>TS", "Best Hit%", "Most enemys" })
				Menu.ta.mqta:addParam("qtainfotxt", "If Protect Option, you have to set a value", SCRIPT_PARAM_INFO, "")
				Menu.ta.mqta:addParam("qtamin", "Range to Protect", SCRIPT_PARAM_SLICE, 250, 0, 1000, 0)			
		-----------------------------------------------------------------------------------------------------			
			Menu.ta:addSubMenu("W Target", "mwta")	
				Menu.ta.mwta:addParam("wta", "W Target", SCRIPT_PARAM_LIST, 2, { "Target Selector", "DMG>HEAL", "HEAL>DMG", "ONLY ADC>TS", "ONLY APC>TS", "ADC>APC>REST","APC>ADC>REST", "Low HP in %", "Low HP total"})
		-----------------------------------------------------------------------------------------------------		
			Menu.ta:addSubMenu("E Target", "meta")	
				Menu.ta.meta:addParam("eta", "E Target", SCRIPT_PARAM_LIST, 1, {"ADC>Rest", "most ad", "most as", "closest to enemy", "ADC>APC>Rest","APC>ADC>Rest", "Low HP in %", "Low HP total" })
		-----------------------------------------------------------------------------------------------------			
			Menu.ta:addSubMenu("R Target", "mrta")
				Menu.ta.mrta:addParam("rta", "R Target", SCRIPT_PARAM_LIST, 4, { "Target Selector", "Prot. ADC", "Prot. APC", "Prot. APC/ADC", "Use MEC" })
				Menu.ta.mrta:addParam("rtainfotxt", "If Protect Option, you have to set a value", SCRIPT_PARAM_INFO, "")
				Menu.ta.mrta:addParam("rtamin", "Range to Protect", SCRIPT_PARAM_SLICE, 250, 0, 1000, 0)
				Menu.ta.mrta:addParam("rtainfotxt2", "If MEC Option, you have to set a value", SCRIPT_PARAM_INFO, "")
				Menu.ta.mrta:addParam("rtamec", "Min. enemy to hit", SCRIPT_PARAM_SLICE, 2, 0, 5, 0)
		-----------------------------------------------------------------------------------------------------		
			Menu.ta:addSubMenu("Orb Target", "mota")
				Menu.ta.mota:addParam("orb", "Orbwalk", SCRIPT_PARAM_LIST, 1, { "COMBO", "ALWAYS", "NEVER" })
				Menu.ta.mota:addParam("ota", "R Target", SCRIPT_PARAM_LIST, 2, { "Target Selector", "most dmg" })
		-----------------------------------------------------------------------------------------------------
		
		-----------------------------------------------------------------------------------------------------
		Menu:addSubMenu("Target tracking", "tartrack")
			Menu.tartrack:addParam("single", "Single target mark", SCRIPT_PARAM_ONOFF, true)
			--Menu.tartrack:addParam("multi", "Multi target mark", SCRIPT_PARAM_ONOFF, false)
		-----------------------------------------------------------------------------------------------------
		

		-----------------------------------------------------------------------------------------------------
		Menu:addSubMenu("Extra", "extra")			
			Menu.extra:addSubMenu("Auto level", "alvl")
				Menu.extra.alvl:addParam("alvlstatus", "Auto lvl skills", SCRIPT_PARAM_ONOFF, false)
				Menu.extra.alvl:addParam("lvlseq", "Choose your lvl Sequence", SCRIPT_PARAM_LIST, 1, { "R>Q>W>E", "R>Q>E>W", "R>W>Q>E", "R>W>E>Q", "R>E>Q>W", "R>E>W>Q" })
			Menu.extra:addSubMenu("Gadgets", "gadg")
				Menu.extra.gadg:addParam("incQrange", "Increased Q Range", SCRIPT_PARAM_ONOFF, false)
				Menu.extra.gadg:addParam("wnfe", "NFE for W", SCRIPT_PARAM_ONOFF, false)
		-----------------------------------------------------------------------------------------------------
_setimpdef()
end


function _loadcrosshair()
	ch.tick = GetTickCount()
	ch.spriteG = GetWebSprite("http://s1.directupload.net/images/140304/id9pi3v6.png")
	ch.spriteR = GetWebSprite("http://s1.directupload.net/images/140314/pdu4a3tk.png")
	ch.spriteO = GetWebSprite("http://s14.directupload.net/images/140314/zh4bnk39.png")
end


function _loadTS()
	qts = TargetSelector(TARGET_PRIORITY, Qrange)
	qts.name = "Q"
	Menu.ta.mqta:addTS(qts)

	wts = TargetSelector(TARGET_LOW_HP_PRIORITY, Wrange-2)
	wts.name = "W"
	Menu.ta.mwta:addTS(wts)
	
	rts = TargetSelector(TARGET_PRIORITY, Rrange-2)
	rts.name = "R"
	Menu.ta.mrta:addTS(rts)
	
	ots = TargetSelector(TARGET_PRIORITY, AArange-2)
	ots.name = "ORB"
	Menu.ta.mota:addTS(ots)
end 


function _setimpdef()
	Menu.extra.alvl.alvlstatus = false
	Menu.keys.permrota = false
	Menu.keys.okdrota = false
	Menu.keys.permhrs = false
	Menu.keys.okdhrs = false
end