<?php exit() ?>--by bothappy 83.38.21.248
--Nidalee by BotHappy

if myHero.charName ~= "Nidalee" then return end

require "SALib"
require "Prodiction"

local Version = 0.02

function OnLoad()
	SAUpdate = Updater("NidaleeUpdate") 
	SAUpdate.LocalVersion = Version 
	SAUpdate.SCRIPT_NAME = "BHNidalee" 
	SAUpdate.SCRIPT_URL = "https://bitbucket.org/BotHappy/bhscripts/raw/master/BHNidalee.lua" 
	SAUpdate.PATH = BOL_PATH.."Scripts\\".."BHNidalee.lua" 
	SAUpdate.HOST = "bitbucket.org" 
	SAUpdate.URL_PATH = "/BotHappy/bhscripts/raw/master/REV/NidaRev.lua" 
	SAUpdate:Run()
	SAAuth = Auth("NidaleeAuth")
	--scriptStart = os.clock()
	Variables()
	Menu()
	if Prodict.status == (0 or 2) then
		for i = 1, heroManager.iCount do
	       local hero = heroManager:GetHero(i)
	       if hero.team ~= myHero.team then
	          ProdQ:GetPredictionOnImmobile(hero, OnCombo)
	          ProdQ:GetPredictionAfterDash(hero, OnCombo)
	       end
	    end	
	end
	print("<font color='#FFFFFF'> >> BH Nidalee v"..tostring(Version).." << </font>")
end

function OnCombo(unit, pos)
	if QReady and ValidTarget(unit) and pos ~= nil then
   		if not ProdQCol:GetMinionCollision(myHero, pos) then 
   			CastSpell(_Q, pos.x, pos.z) 
   		end
	end
end

function OnTick()
	--if os.clock() > scriptStart + 20 and not SAAuth:IsAuthed() then UnloadScript(debug.getinfo(2).source) end
	Checks()
	GetDamages()
	if Helper:__validTarget(Target) then
		if Menu.combosettings.Poke then
			Poke(Target)
		end
		if Menu.combosettings.Combo then
			Combo(Target)
		end
	else
		if Menu.combosettings.Poke and Menu.combosettings.MovePoke then
			MoveToCursor()
		end
	end
	if Menu.teamsettings.TeamHeal then Heal() end
	if Menu.othersettings.Pounce then PounceMouse() end
	if Menu.othersettings.HumanJumper then HumanJumper() end
	--if Menu.othersettings.Helper then AutoPounce() end
end

function OnDraw()
	if SAAuth:IsAuthed() == false then 
		return 
	end
	if not Menu.drawsettings.Deactive then
		if not Menu.drawsettings.circlesettings.NotRdy then
			if HumanForm then
				if Menu.drawsettings.circlesettings.DrawQ then
					Drawer:DrawCircleHero(myHero, SkillQ.range, Drawer.Cyan, QReady)
				end
				if Menu.drawsettings.circlesettings.DrawW then
					Drawer:DrawCircleHero(myHero, SkillW.range, Drawer.Red, WReady)
				end
				if Menu.drawsettings.circlesettings.DrawE then
					Drawer:DrawCircleHero(myHero, SkillE.range, Drawer.Green, EReady)
				end
				if Target ~= nil and Menu.drawsettings.circlesettings.Target then
					Drawer:DrawCircleHero(Target, 60, Drawer.BlueViolet, true)
				end
			elseif Menu.drawsettings.circlesettings.Cougar then
				Drawer:DrawCircleHero(myHero, SkillWM.range, Drawer.Red, WMReady)
				Drawer:DrawCircleHero(myHero, SkillEM.range, Drawer.Green, EMReady)
			end
		else
			if HumanForm then
				if Menu.drawsettings.circlesettings.DrawQ then
					Drawer:DrawCircleHero(myHero, SkillQ.range, Drawer.Cyan, true)
				end
				if Menu.drawsettings.circlesettings.DrawW then
					Drawer:DrawCircleHero(myHero, SkillW.range, Drawer.Red, true)
				end
				if Menu.drawsettings.circlesettings.DrawE then
					Drawer:DrawCircleHero(myHero, SkillE.range, Drawer.Green, true)
				end
				if Target ~= nil and Menu.drawsettings.circlesettings.Target then
					Drawer:DrawCircleHero(Target, 60, Drawer.BlueViolet, true)
				end
			elseif Menu.drawsettings.circlesettings.Cougar then
				Drawer:DrawCircleHero(myHero, SkillWM.range, Drawer.Red, true)
				Drawer:DrawCircleHero(myHero, SkillEM.range, Drawer.Green, true)
			end
		end
		for i = 1, EnemysInTable do
        	local EnemyDraws = EnemyTable[i].hero
        	if Helper:__validTarget(EnemyDraws) then
				Drawer:DrawOnHPBar(EnemyDraws, EnemyTable[i].SpearText, Drawer.White)
			end
		end
		for i, spot in ipairs(pounceSpots) do
			local spot1x = spot.fromx
			local spot1z = spot.fromz
			if math.sqrt((spot1x-myHero.x)^2+(spot1z-myHero.z)^2) < Menu.othersettings.DrawD then
				if Menu.othersettings.DrawP then
					DrawCircle2(spot.fromx, spot.fromy, spot.fromz, 100, ARGB(255,255,255,255))
				end
				if Menu.othersettings.DrawP2 then
					DrawCircle2(spot.tox, spot.toy, spot.toz, 100, ARGB(255,0,255,255))
				end
			end
		end
	end
	--DrawCircle(changePos.x, changePos.y, changePos.z, 30, 0xFFFFFF)
end

-------------------------------------------------------
--					Aux Functions					 --
-------------------------------------------------------

function Variables()
	--Skills
	SkillQ = {range = 1550}
	SkillW = {range = 900}
	SkillE = {range = 600}
	SkillWM = {range = 375}
	SkillEM = {range = 275}

	--Inicializo Prodiction para la Q
	Prodict = ProdictManager.GetInstance()
	ProdQ = Prodict:AddProdictionObject(_Q, SkillQ.range, 1325, 0.2, 60)
	ProdQCol = Collision(SkillQ.range, 1325, 0.2, 60)

	--Target Selector
	ts = TargetSelector(TARGET_LESS_CAST, SkillQ.range, DAMAGE_MAGIC, true)
	ts.name = "Nidalee"

	Priorities:Load()

	--Inicio de Libreria
	TSAdvanced = CombatHandler(ts)
	ORB = Orbwalker("FallbackOrbwalker")

	--Checkeo de Summoners
	IgniteSlot = CheckSummoner("SummonerDot")

	--Inicializo las variables
	HumanForm = true

	EnemyTable = {}
	EnemysInTable = 0
    enemyHeroes = GetEnemyHeroes()

	Recall = false

	--Asigno a cada Enemigo un texto
	for i=1, heroManager.iCount do
		local champ = heroManager:GetHero(i)
		if champ.team ~= myHero.team then
			EnemysInTable = EnemysInTable + 1
			EnemyTable[EnemysInTable] = { hero = champ, Name = champ.charName, SpearText = "", DmgText = ""}
		end
	end

	pounceSpots = 
	{
		{ fromx = 5050.10,  fromy = -63.04, fromz = 10514.81, tox = 5300.10,  toy = -63.04, toz = 10990},
		{ fromx = 6700.00,  fromy = 54.95, fromz = 8050.00 ,  tox = 6850.00,  toy = 54.95, toz = 8250.00 },
		{ fromx = 3500.00,  fromy = 54.95, fromz = 7150.00, tox = 3650.00,  toy = 54.95, toz = 7500.00},  
		{ fromx = 2800.00,   fromy = 54.95,  fromz = 6100.00, tox = 2600.00,  toy = 54.95, toz = 5900.00},  
		{ tox = 2800.00,   toy = 54.95,  toz = 6100.00, fromx = 2600.00,  fromy = 54.95, fromz = 5900.00},
		{ fromx = 2700.00,  fromy = 54.95, fromz = 6400.00, tox = 2400.00,  toy = 54.95, toz = 6400.00}, 
		{ tox = 2700.00,  toy = 54.95, toz = 6400.00, fromx = 2400.00,  fromy = 54.95, fromz = 6400.00}, 
		{ fromx = 6027.396484375,  fromy = 51.673580169678, fromz =  4842.2021484375, tox = 6393.6875,  toy = 51.673400878906, toz = 4768.2412109375}, 
		{ fromx = 6364.6201171875,  fromy = 51.673400878906, fromz =  4814.49609375, tox = 5980.9848632813,  toy = 51.673568725586, toz = 4824.7397460938}, 
		{ fromx = 8621.3291015625,  fromy = 56.004802703857, fromz =  4115.5546875, tox = 9011,  toy = -63.276527404785, toz = 4149}, 
		{ fromx = 8962.626953125,  fromy = -63.257339477539, fromz =  4120.89453125, tox = 8593.63671875,  toy = 56.045333862305, toz = 4026.2592773438}, 
		{ fromx = 3193.0754394531,  fromy = 54.008750915527, fromz =  6831.8823242188, tox = 3280.9321289063,  toy = 55.605667114258, toz = 6468.1293945313}, 
		{ fromx = 7629.6577148438,  fromy = 54.545547485352, fromz =  9562.7314453125, tox = 8009.6689453125,  toy = 53.529735565186, toz = 9564.94140625}, 
		{ fromx = 6360.8833007813,  fromy = 56.018459320068, fromz =  8773.2783203125, tox = 6069,  toy = -65.075157165527, toz = 8511}, 
		{ fromx = 7705.1127929688,  fromy = -63.986232757568, fromz =  5926.677734375, tox = 7447,  toy = 54.387222290039, toz = 5633}, 
		{ fromx = 7505.7788085938,  fromy = 53.640930175781, fromz =  5729.38671875, tox = 7718.6357421875,  toy = -64.580268859863, toz = 6043.7529296875}, 
		{ fromx = 9302.7666015625,  fromy = 65.312408447266, fromz =  2881.455078125, tox = 9143.513671875,  toy = 68.019905090332, toz = 2538.7836914063}, 
		{ fromx = 9199.3515625,  fromy = 67.930084228516, fromz =  2569.0808105469, tox = 9326.7177734375,  toy = 63.658580780029, toz = 2926.3090820313}, 
		{ fromx = 11284.85546875,  fromy = -62.74866104126, fromz =  4453.1943359375, tox = 11535.306640625,  toy = 53.881202697754, toz = 4742.6064453125}, 
		{ fromx = 11533.138671875,  fromy = 51.938335418701, fromz =  4570.9194335938, tox = 11177,  toy = -63.058109283447, toz = 4403}, 
		{ fromx = 11550.590820313,  fromy = 56.93518447876, fromz =  5328.91015625, tox = 11883.37890625,  toy = 51.922733306885, toz = 5224.6118164063}, 
		{ fromx = 11872.857421875,  fromy = 51.929985046387, fromz =  5205.5092773438, tox = 11501.424804688,  toy = 56.76513671875, toz = 5334.6875}, 
		{ fromx = 10701.350585938,  fromy = 54.87113571167, fromz =  6947.1342773438, tox = 10573.72265625,  toy = 55.350219726563, toz = 7303.66796875}, 
		{ fromx = 10566.223632813,  fromy = 55.350234985352, fromz =  7307.4790039063, tox = 10639.080078125,  toy = 54.870681762695, toz = 6943.8696289063}, 
		{ fromx = 11280.172851563,  fromy = 61.054931640625, fromz =  8299.990234375, tox = 11559.912109375,  toy = 53.864097595215, toz = 8549.4033203125}, 
		{ fromx = 11495.775390625,  fromy = 53.843658447266, fromz =  8539.431640625, tox = 11130.583007813,  toy = 65.873527526855, toz = 8431.8076171875}, 
		{ fromx = 10424.12109375,  fromy = 66.058128356934, fromz =  8330.75390625, tox = 10075.438476563,  toy = 64.491928100586, toz = 8493.009765625}, 
		{ fromx = 2687.2644042969,  fromy = 53.481735229492, fromz =  9749.736328125, tox = 2925,  toy = -64.993209838867, toz = 10061}, 
		{ fromx = 2897.8869628906,  fromy = -65.339836120605, fromz =  10033.421875, tox = 2585,  toy = 53.412200927734, toz = 9793}, 
		{ fromx = 2465.3930664063,  fromy = 53.765937805176, fromz =  8965.55078125, tox = 2091.0083007813,  toy = 54.921485900879, toz = 8913.1396484375}, 
		{ fromx = 2136.8247070313,  fromy = 54.921272277832, fromz =  8895.6806640625, tox = 2514.048828125,  toy = 53.764739990234, toz = 8921.96484375}, 
		{ fromx = 6433.115234375,  fromy = -64.309341430664, fromz =  8367.8818359375, tox = 6721,  toy = 56.018997192383, toz = 8637}, 
		{ fromx = 6660.041015625,  fromy = 56.01900100708, fromz =  8525.6875, tox = 6387,  toy = -61.964237213135, toz = 8243}, 
		{ fromx = 6174.0024414063,  fromy = 54.635597229004, fromz =  11402.020507813, tox = 6007.6240234375,  toy = 39.543445587158, toz = 11761.15625}, 
		{ fromx = 6036.3188476563,  fromy = 39.548717498779, fromz =  11779.643554688, tox = 6244.2426757813,  toy = 54.633190155029, toz = 11452.513671875}, 
		{ fromx = 7947.4345703125,  fromy = 53.529407501221, fromz =  9533.22265625, tox = 7566.046875,  toy = 55.090217590332, toz = 9558.4248046875}, 
		{ fromx = 8781.9296875,  fromy = 58.675441741943, fromz =  3654.2075195313, tox = 9072.9990234375,  toy = -63.249221801758, toz = 3898.33203125}, 
		{ fromx = 9057.431640625,  fromy = -63.247486114502, fromz =  3920.3110351563, tox = 8704.1748046875,  toy = 57.134674072266, toz = 3808.2175292969}, 
		{ fromx = 6956.8618164063,  fromy = 55.664554595947, fromz =  5678.4897460938, tox = 6744.5205078125,  toy = 61.12858581543, toz = 5370.822265625}, 
		{ fromx = 6758.6586914063,  fromy = 59.89038848877, fromz =  5325.1083984375, tox = 6894.94921875,  toy = 55.633464813232, toz = 5689.4946289063}, 
		{ fromx = 7138.3051757813,  fromy = 56.01900100708, fromz =  8747.099609375, tox = 7286.6284179688,  toy = 55.58337020874, toz = 9101.314453125}, 
		{ fromx = 7278.5234375,  fromy = 55.605682373047, fromz =  9064.7158203125, tox = 7018.9892578125,  toy = 56.01900100708, toz = 8801.1181640625}, 
		{ fromx = 5388.392578125,  fromy = 55.07043838501, fromz =  9973.6650390625, tox = 5027,  toy = -63.082813262939, toz = 10117}, 
		{ fromx = 4999.95703125,  fromy = -63.083316802979, fromz =  10071.759765625, tox = 5383.3505859375,  toy = 55.09606552124, toz = 10045.358398438}, 
		{ fromx = 5363.2934570313,  fromy = 54.282604217529, fromz =  10617.295898438, tox = 4998.5131835938,  toy = -63.036136627197, toz = 10514.624023438}, 
		{ fromx = 4025.4812011719,  fromy = -63.151443481445, fromz =  10226.96875, tox = 3658.6271972656,  toy = -64.08642578125, toz = 10142.516601563}, 
		{ fromx = 7969.4956054688,  fromy = 55.384750366211, fromz =  3025.1618652344, tox = 7969,  toy = 54.276401519775, toz = 2637.9768066406}, 
		{ fromx = 8044.5678710938,  fromy = 54.276397705078, fromz =  2649.654296875, tox = 8056.7739257813,  toy = 56.413684844971, toz = 3028.8168945313}, 
		{ fromx = 10071.381835938,  fromy = 66.61124420166, fromz =  8414.41796875, tox = 10417.513671875,  toy = 65.924545288086, toz = 8279.259765625}, 
		{ fromx = 3295.3630371094,  fromy = 55.604820251465, fromz =  6510.509765625, tox = 3289.1625976563,  toy = 53.501697540283, toz = 6881.509765625}, 
		{ fromx = 1616.8380126953,  fromy = 54.923732757568, fromz =  8415.9248046875, tox = 1251.5101318359,  toy = 50.740386962891, toz = 8528.34375}, 
		{ fromx = 1229.8566894531,  fromy = 50.664134979248, fromz =  8517.7666015625, tox = 1590.5450439453,  toy = 54.923778533936, toz = 8389.4482421875}, 
		{ fromx = 4739.0541992188,  fromy = 51.052474975586, fromz =  11588.2578125, tox = 4789.4423828125,  toy = 41.499092102051, toz = 11960.984375}, 
		{ fromx = 4750.1713867188,  fromy = 41.581077575684, fromz =  11867.202148438, tox = 4709.2783203125,  toy = 51.196998596191, toz = 11498.553710938}, 
		{ fromx = 3603.0581054688,  fromy = 55.612461090088, fromz =  6230.564453125, tox = 3827.1840820313,  toy = 55.373138427734, toz = 5931.0795898438}, 
		{ fromx = 3807.4829101563,  fromy = 55.550140380859, fromz =  5905.2109375, tox = 3522.9526367188,  toy = 55.611869812012, toz = 6143.4833984375}, 
		{ fromx = 5647.4296875,  fromy = 55.100242614746, fromz =  9579.4169921875, tox = 5670.7138671875,  toy = -62.240566253662, toz = 9191.9638671875}, 
		{ fromx = 12419,  fromy = 54.801120758057, fromz =  6165, tox = 12774.104492188,  toy = 57.867492675781, toz = 6165}, 
		{ fromx = 4748.576171875,  fromy = -62.587707519531, fromz =  9445.8876953125, tox = 4715.982421875,  toy = -63.093677520752, toz = 9823.5673828125}, 
		{ fromx = 4717.1611328125,  fromy = -63.08674621582, fromz =  9705.1611328125, tox = 4715.0673828125,  toy = -63.347808837891, toz = 9325.974609375}, 
		{ fromx = 7301.8623046875,  fromy = -43.863998413086, fromz =  6229.6245117188, tox = 7137,  toy = 55.59769821167, toz = 5875}, 
		{ fromx = 7185.2275390625,  fromy = 55.59769821167, fromz =  5897.9340820313, tox = 7391.1909179688,  toy = -66.484939575195, toz = 6218.3012695313}, 
		{ fromx = 3496.2651367188,  fromy = 55.02318572998, fromz =  7477.1059570313, tox = 3495.0832519531,  toy = 54.439247131348, toz = 7102.5991210938}, 
		{ fromx = 9399,  fromy = 52.484180450439, fromz =  12015, tox = 9724.76171875,  toy = 106.21723175049, toz = 12162.502929688}, 
		{ fromx = 9763.21875,  fromy = 106.22006225586, fromz =  12064.305664063, tox = 9384.583984375,  toy = 52.484340667725, toz = 12032.125}, 
		{ fromx = 8344.5859375,  fromy = 54.895027160645, fromz =  9096.623046875, tox = 8356.2822265625,  toy = 53.534721374512, toz = 9467.4248046875}, 
		{ fromx = 8290.26953125,  fromy = 53.530422210693, fromz =  9395.4912109375, tox = 8257.658203125,  toy = 54.895046234131, toz = 9011.2509765625}, 
		{ fromx = 8499.0849609375,  fromy = 52.796592712402, fromz =  9748.5048828125, tox = 8394.33984375,  toy = 53.637596130371, toz = 10111.264648438}, 
		{ fromx = 8391.26171875,  fromy = 53.606884002686, fromz =  10026.416992188, tox = 8486.1083984375,  toy = 53.145664215088, toz = 9667.525390625}, 
		{ fromx = 6408.8051757813,  fromy = 54.63500213623, fromz =  10802.88671875, tox = 6138.6069335938,  toy = 54.142120361328, toz = 11071.393554688}, 
		{ fromx = 12785,  fromy = 58.493560791016, fromz =  6061, tox = 12420.41015625,  toy = 54.799705505371, toz = 6061}, 
		{ fromx = 10635.231445313,  fromy = 65.302719116211, fromz =  7976.4921875, tox = 10844.665039063,  toy = 55.36051940918, toz = 7658.5346679688}, 
		{ fromx = 10796.970703125,  fromy = 55.354804992676, fromz =  7631.8203125, tox = 10715.32421875,  toy = 64.996284484863, toz = 7986.6645507813}, 
		{ fromx = 2655,  fromy = 105.71267700195, fromz =  4289, tox = 2771.1494140625,  toy = 57.085289001465, toz = 4633.74609375}, 
		{ fromx = 2785,  fromy = 57.104434967041, fromz =  4647, tox = 2694.7729492188,  toy = 106.55506134033, toz = 4292.3310546875}, 
		{ fromx = 4209.234375,  fromy = 54.935352325439, fromz =  5679.076171875, tox = 4305.357421875,  toy = 53.387035369873, toz = 5315.1220703125}, 
		{ fromx = 3962.3305664063,  fromy = 51.937511444092, fromz =  7972.1245117188, tox = 3630.1025390625,  toy = 54.101119995117, toz = 7768.8837890625},
		{ fromx = 3657.5693359375,  fromy = 53.934204101563, fromz =  7802.775390625, tox = 3964.5805664063,  toy = 51.945098876953, toz = 8043.6088867188},
		{ fromx = 10035.963867188,  fromy = 55.154335021973, fromz =  6459.0268554688, tox = 10340.025390625,  toy = 54.86909866333, toz = 6673.3002929688},
		{ tox = 10035.963867188,  toy = 55.154335021973, toz =  6459.0268554688, fromx = 10340.025390625,  fromy = 54.86909866333, fromz = 6673.3002929688},
		{ fromx = 7855.5883789063,  fromy = 56.438591003418, fromz =  3349.5710449219, tox = 7656.7197265625,  toy = 56.867298126221, toz = 3669.5600585938},
		{ fromx = 7638.8984375,  fromy = 56.867309570313, fromz =  3683.9421386719, tox = 7745.6748046875,  toy = 54.70597076416, toz = 3316.4208984375},
		{ fromx = 3906.5844726563,  fromy = 53.449546813965, fromz =  7205.63671875, tox = 3635.6733398438,  toy = 54.867137908936, toz = 7476.3334960938},
		{ fromx = 11663.713867188,  fromy = 55.458312988281, fromz =  8057.57421875, tox = 11301.641601563,  toy = 59.172275543213, toz = 8159.6928710938},
		{ fromx = 11381,  fromy = 50.349140167236, fromz =  9733, tox = 11483.938476563,  toy = 106.53845214844, toz = 10074.44140625},
		{ fromx = 11491,  fromy = 106.53971099854, fromz =  10095, tox = 11434.098632813,  toy = 50.349784851074, toz = 9730.1171875},
		{ fromx = 4117,  fromy = 110.18843078613, fromz =  2927, tox = 4474.3818359375,  toy = 54.039722442627, toz = 3010.8728027344},
		{ fromx = 4473,  fromy = 54.04020690918, fromz =  3009, tox = 4119.6821289063,  toy = 110.16602325439, toz = 2921.1838378906},
		{ fromx = 5582.037109375,  fromy = 51.680099487305, fromz =  4381.2333984375, tox = 5508.341796875,  toy = 52.344429016113, toz = 4751.2734375},
		{ fromx = 5799.3657226563,  fromy = 51.673671722412, fromz =  5047.4682617188, tox = 5747.689453125,  toy = 54.920425415039, toz = 5418.072265625},
		{ fromx = 5749.0727539063,  fromy = 54.92049407959, fromz =  5442.3271484375, tox = 5778.0341796875,  toy = 51.673679351807, toz = 5062.4853515625},
		{ fromx = 5498.7192382813,  fromy = 51.968696594238, fromz =  4715.8745117188, tox = 5607.236328125,  toy = 51.680099487305, toz = 4355.568359375},
		{ fromx = 6907.5615234375,  fromy = 56.019004821777, fromz =  8287.216796875, tox = 6648.2529296875,  toy = -63.042301177979, toz = 8019.3041992188},
		{ fromx = 9149,  fromy = 66.709335327148, fromz =  3337, tox = 9327,  toy = -63.259346008301, toz = 3667},
		{ fromx = 9339,  fromy = -63.259101867676, fromz =  3669, tox = 9185.1826171875,  toy = 65.361930847168, toz = 3333.2795410156},
		{ fromx = 9733.2001953125,  fromy = 52.481067657471, fromz =  11056.283203125, tox = 10060.69921875,  toy = 106.22298431396, toz = 11247.291992188},
		{ tox = 9733.2001953125,  toy = 52.481067657471, toz =  11056.283203125, fromx = 10060.69921875,  fromy = 106.22298431396, fromz = 11247.291992188},
		{ fromx = 9338.6142578125,  fromy = 52.781448364258, fromz =  12813.484375, tox = 9711.140625,  toy = 106.20919799805, toz = 12789.477539063},
		{ fromx = 9712.142578125,  fromy = 106.20919799805, fromz =  12803.951171875, tox = 9368.14453125,  toy = 102.27192687988, toz = 12979.302734375},
		{ fromx = 1592.0592041016,  fromy = 108.56233215332, fromz =  4469.505859375, tox = 1631,  toy = 56.029193878174, toz = 4853},
		{ fromx = 1560.5727539063,  fromy = 56.144931793213, fromz =  4858.7158203125, tox = 1593.6468505859,  toy = 108.56246948242, toz = 4488.7553710938},
	}
end

function Menu()
	Menu = scriptConfig("BH Nidalee v."..tostring(Version), "BHNida")
	Menu:addSubMenu("["..myHero.charName.." - Combo Settings]", "combosettings")
		Menu.combosettings:addParam("Poke", "Poke", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("X"))
		Menu.combosettings:addParam("Combo", "Combo Mode", SCRIPT_PARAM_ONKEYDOWN, false, 32)
		Menu.combosettings:addParam("Transform", "Transform on Combo Mode", SCRIPT_PARAM_ONOFF, true)
		Menu.combosettings:addParam("AutoCougar", "AutoCougar at X Range", SCRIPT_PARAM_SLICE, 400, 300, 550, 0)
		Menu.combosettings:addParam("AutoHuman", "AutoHuman at X Range", SCRIPT_PARAM_SLICE, 650, 500, 900, 0)
		Menu.combosettings:addParam("UseE", "Use E on Human Combo", SCRIPT_PARAM_ONOFF, false)
		Menu.combosettings:addParam("MovePoke", "Move on Poke", SCRIPT_PARAM_ONOFF, true)
	Menu:addSubMenu("["..myHero.charName.." - Team Settings]", "teamsettings")
		Menu.teamsettings:addParam("TeamHeal", "Auto Heal", SCRIPT_PARAM_ONOFF, false)
		Menu.teamsettings:addParam("HealHealth", "Heal if below X% health", SCRIPT_PARAM_SLICE, 60, 1, 100, 0)
		Menu.teamsettings:addParam("HealMana", "Heal if mana is above X", SCRIPT_PARAM_SLICE, 200, 100, 4000, 0)
		--Menu.teamsettings:addParam("ForceHeal", "Force Heal on Cougar", SCRIPT_PARAM_ONOFF, false)
		Menu.teamsettings:addSubMenu("[Heal Selection]", "healsettings")
			for i=1, heroManager.iCount do
				local teammate = heroManager:GetHero(i)
				if teammate.team == myHero.team then Menu.teamsettings.healsettings:addParam("teamateheal"..i, "Heal "..teammate.charName, SCRIPT_PARAM_ONOFF, true) end
			end
	Menu:addSubMenu("["..myHero.charName.." - Draw Settings]", "drawsettings")
		Menu.drawsettings:addSubMenu("[Circle Settings]", "circlesettings")
			Menu.drawsettings.circlesettings:addParam("DrawQ", "Draw Q Range", SCRIPT_PARAM_ONOFF, true)
			Menu.drawsettings.circlesettings:addParam("DrawW", "Draw W Range", SCRIPT_PARAM_ONOFF, false)
			Menu.drawsettings.circlesettings:addParam("DrawE", "Draw E Range", SCRIPT_PARAM_ONOFF, true)
			Menu.drawsettings.circlesettings:addParam("Cougar", "Draw Cougar Ranges", SCRIPT_PARAM_ONOFF, true)
			Menu.drawsettings.circlesettings:addParam("Target", "Draw Circle around Target", SCRIPT_PARAM_ONOFF, true)
			Menu.drawsettings.circlesettings:addParam("NotRdy", "Draw even if not ready", SCRIPT_PARAM_ONOFF, false)
		Menu.drawsettings:addParam("Deactive", "Deactive all Draws", SCRIPT_PARAM_ONOFF, false)
		Menu.drawsettings:addParam("Kill", "Kill Texts", SCRIPT_PARAM_ONOFF, true)
		Menu.drawsettings:addParam("lagfree", "Lag Free Circles (Restart)", SCRIPT_PARAM_ONOFF, false)
	Menu:addSubMenu("["..myHero.charName.." - Other Settings]", "othersettings")
		Menu.othersettings:addParam("Pounce", "Pounce To Mouse", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("G"))
		Menu.othersettings:addParam("HumanJumper", "Pounce To Human", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("V"))
		--Menu.othersettings:addParam("Helper", "Pounce Helper",  SCRIPT_PARAM_ONKEYDOWN, false, string.byte("C"))
		Menu.othersettings:addParam("DrawP", "Draw Pounce points",  SCRIPT_PARAM_ONOFF, false)
		Menu.othersettings:addParam("DrawP2", "Draw landing points",  SCRIPT_PARAM_ONOFF, false)
		Menu.othersettings:addParam("DrawD", "Draw Circles if distance < X", SCRIPT_PARAM_SLICE, 2000, 0, 10000, 0)

	Menu.combosettings:permaShow("Poke")
	Menu.combosettings:permaShow("Combo")
	Menu.teamsettings:permaShow("TeamHeal")
	--Menu.othersettings:permaShow("Helper")
	Menu:addTS(ts)

	if Menu.drawsettings.lagfree then
		_G.DrawCircle = DrawCircle2
	end
end

function CastQ(unit, pos)
    if ValidTarget(unit) and pos ~= nil then
    	local willCollide = ProdQCol:GetMinionCollision(myHero, pos)
   		if not willCollide then CastSpell(_Q, pos.x, pos.z) end
    end
end

function Checks()
	HumanForm = (myHero:GetSpellData(_Q).name == "JavelinToss")

	QReady = (myHero:CanUseSpell(_Q) == READY and HumanForm)
	WReady = (myHero:CanUseSpell(_W) == READY and HumanForm)
	EReady = (myHero:CanUseSpell(_E) == READY and HumanForm)
	RReady = (myHero:CanUseSpell(_R) == READY)

	QMReady = (myHero:CanUseSpell(_Q) == READY and not HumanForm)
	WMReady = (myHero:CanUseSpell(_W) == READY and not HumanForm)
	EMReady = (myHero:CanUseSpell(_E) == READY and not HumanForm)

	IgniteReady = (IgniteSlot ~=nil and myHero:CanUseSpell(IgniteSlot) == READY)

	Target = TSAdvanced:GetTarget()
	changePos = myHero + (Vector(mousePos.x, mousePos.y, mousePos.z) - Vector(myHero.x,myHero.y,myHero.z)):normalized()*29
end

function MoveToCursor()
	if GetDistance(mousePos) > 1 or LastAnimation == "Idle1" then
		local moveToPos = myHero + (Vector(mousePos) - myHero):normalized()*300
		Packet('S_MOVE', {x = moveToPos.x, y = moveToPos.z}):send()
	end	
end

function OnAnimation(Unit,AnimationName)
	if Unit.isMe and LastAnimation ~= AnimationName then LastAnimation = AnimationName end
end

-- function AutoPounce()
-- 	for i, spot in ipairs(pounceSpots) do
-- 		if math.sqrt((spot.fromx-mousePos.x)^2+(spot.fromz-mousePos.z)^2) < 100 then
-- 			myHero:MoveTo(spot.fromx, spot.fromz)
-- 			spot1fx = spot.fromx
-- 			spot1fy = spot.fromy
-- 			spot1fz = spot.fromz
-- 			spot1tx = spot.tox
-- 			spot1ty = spot.toy
-- 			spot1tz = spot.toz
-- 		end
-- 		if spot1ty and spot1tx and spot1fz and spot1tz and spot1fx and spot1fx then
-- 			if math.sqrt((spot1fx-myHero.x)^2+(spot1fz-myHero.z)^2) < 50 then
-- 				print("Hi")
-- 				local movePos = myHero + Vector(spot1fx-spot1tx, spot1fy-spot1ty, spot1fz-spot1tz):normalized()*30
-- 				Packet('S_MOVE', {type = 2, x = movePos.x, y = movePos.z})
-- 			end
-- 		end
-- 	end
-- end

-------------------------------------------------------
--					Own Functions					 --
-------------------------------------------------------

function Heal()
    if HumanForm == false then return end
    for i=1, heroManager.iCount do
		local allytarget = heroManager:GetHero(i)
		if Menu.teamsettings.healsettings["teamateheal"..i] and not allytarget.dead and not Recall and allytarget.health > 0 then
			if allytarget.health < ((Menu.teamsettings.HealHealth)/100)*allytarget.maxHealth and Helper:__correctDistance(allytarget, SkillE.range) and myHero.mana > Menu.teamsettings.HealMana then
				if HumanForm and EReady then
					CastSpell(_E, allytarget)
				-- elseif Menu.teamsettings.ForceHeal and RReady and not HumanForm then
				-- 	CastSpell(_R)
				-- 	CastSpell(_E, allytarget)
				end
			end
		end
	end
end

function Poke(unit)
	if Menu.combosettings.MovePoke then
		MoveToCursor()
	end
	if Helper:__validTarget(unit, SkillQ.range) and QReady then
		ProdQ:GetPredictionCallBack(unit, CastQ)
	end
end

--Función de daño de las spears--
--Lo que he hecho es una línea que va cambiando entre los 
--dos puntos, y pillar el punto justo donde están.

function SpearDmg(unit)
	local distance = GetDistance(unit)
	if distance < 525 then
		return getDmg("Q", unit, myHero)
	else
		local mindmg = 43.75*myHero:GetSpellData(_Q).level+11.25+0.65*myHero.ap
		local maxdmg = 109.375*myHero:GetSpellData(_Q).level+33.75+1.625*myHero.ap
		local percent = (distance-525)/975
		if percent > 1 then
			percent = 1
		elseif percent < 0 then
			percent = 0
		end
		local dmg = (mindmg*(1-percent)+maxdmg*(percent))

		return myHero:CalcMagicDamage(unit, dmg)
	end
end

--Combo. Bastante básico pero bueno.
function Combo(unit)
	local qMana = myHero:GetSpellData(_Q).mana
	local eMana = myHero:GetSpellData(_E).mana
	local ignDmg = getDmg("IGNITE", unit, myHero)

	if Helper:__validTarget(unit, SkillQ.range) then
		CastItems(unit)
		if IgniteReady and unit.health < ignDmg and GetDistance(unit) > (myHero.range-40) then
			CastSpell(IgniteSlot, unit)
		end
		if HumanForm then
			if QReady and myHero.mana > qMana then
				ProdQ:GetPredictionCallBack(unit, CastQ)
			end
			if RReady and Menu.combosettings.Transform and GetDistance(unit) < Menu.combosettings.AutoCougar then
				CastSpell(_R)
			end
		else
			if WMReady then
				Packet('S_MOVE', {type=2, x = unit.x, y = unit.z}):send()
				DelayAction(function() CastSpell(_W) end, 0.09)
			elseif EMReady then
				Packet('S_MOVE', {type=2, x = unit.x, y = unit.z}):send()
				DelayAction(function() CastSpell(_E) end, 0.09)
			elseif QMReady then
				CastSpell(_Q)
			end
			if RReady and Menu.combosettings.Transform and GetDistance(unit) > Menu.combosettings.AutoHuman then
				CastSpell(_R)
			end
		end
	end
end

function HumanJumper()
	if not HumanForm then
		if RReady and WMReady then
			Packet('S_MOVE', {type=2, x = changePos.x, y = changePos.z}):send()
			DelayAction(function()
				CastSpell(_W)
				CastSpell(_R)
				CastSpell(_Q)
				end, 0.07)
			DelayAction(function() Packet('S_MOVE', {type=10, x = mousePos.x, y = mousePos.z}):send() end, 0.11)
		end
	end
end

function PounceMouse()
	if WMReady then
	   	Packet('S_MOVE', {type=2, x = changePos.x, y = changePos.z}):send()
		DelayAction(function() CastSpell(_W) end, 0.07)
		DelayAction(function() Packet('S_MOVE', {type=10, x = mousePos.x, y = mousePos.z}):send() end, 0.11)
	end
	-- Check for FindNearestNonWall in front of the champ
end

function GetDamages() -- Have to improve it much
    for i = 1, EnemysInTable do
        local unit = EnemyTable[i].hero
		local Health = unit.health + ((unit.hpRegen/5) * 1)

		local ATTDmg = getDmg("AD", unit, myHero)
		local qDmg = (SpearDmg(unit) or 0)
		local qmDmg = (getDmg("QM", unit, myHero) or 0)
		local wmDmg = (getDmg("WM", unit, myHero) or 0)
		local emDmg = (getDmg("EM", unit, myHero) or 0)

		if Helper:__validTarget(unit) then
			local numberspears = Health/qDmg
			EnemyTable[i].SpearText = "Spears: "..RoundUp(numberspears)
		end
	end
end

function CastItems(unit)
	if Helper:__validTarget(unit) then
		local Distance = GetDistance(unit, myHero)
		local ItemArray = {
			["HXG"] = {id = 3146, range = 700},
			["DFG"] = {id = 3128, range = 750},
			["BLACKFIRE"] = {id = 3188, range = 750},
			["BWC"] = {id = 3144, range = 450},
			["TIAMAT"] = {id = 3077, range = 350},
			["HYDRA"] = {id = 3074, range = 350}
		}

		for _, item in pairs(ItemArray) do
			if GetInventoryItemIsCastable(item.id) and Distance <= item.range then
				CastItem(item.id, unit)
			end
		end
	end
end

-------------------------------------------------------
--					Other Functions					 --
-------------------------------------------------------

function OnCreateObj(obj)
	if obj.name:find("TeleportHome.troy") then
		if GetDistance(obj, myHero) <= 70 then
			Recall = true
		end
	end
end

function OnDeleteObj(obj)
	if obj.name:find("TeleportHome.troy") then
		Recall = false
	end
end

function FindNearestNonWall(x0, y0, z0, maxRadius, precision)
    local vec, radius = D3DXVECTOR3(x0, y0, z0), 1
    if not IsWall(vec) then return vec end
    x0, z0, maxRadius, precision = math.round(x0 / precision) * precision, math.round(z0 / precision) * precision, maxRadius and math.floor(maxRadius / precision) or math.huge, precision or 50
    local function checkP(x, y) 
        vec.x, vec.z = x0 + x * precision, z0 + y * precision 
        return not IsWall(vec) 
    end
    while radius <= maxRadius do
        if checkP(0, radius) or checkP(radius, 0) or checkP(0, -radius) or checkP(-radius, 0) then 
            return vec 
        end
        local f, x, y = 1 - radius, 0, radius
        while x < y - 1 do
            x = x + 1
            if f < 0 then 
                f = f + 1 + 2 * x
            else 
                y, f = y - 1, f + 1 + 2 * (x - y)
            end
            if checkP(x, y) or checkP(-x, y) or checkP(x, -y) or checkP(-x, -y) or 
               checkP(y, x) or checkP(-y, x) or checkP(y, -x) or checkP(-y, -x) then 
                return vec 
            end
        end
        radius = radius + 1
    end
end